import fs from 'node:fs/promises';
import path from 'node:path';
import { build } from 'esbuild';
import chokidar from 'chokidar';
import * as sass from 'sass';

const rootDir = process.cwd();
const sourceDir = path.join(rootDir, 'src');
const outputRoot = path.join(rootDir, 'assets');

async function collectFiles(directory, extension, files = []) {
  const entries = await fs.readdir(directory, { withFileTypes: true });

  for (const entry of entries) {
    const absolutePath = path.join(directory, entry.name);

    if (entry.isDirectory()) {
      await collectFiles(absolutePath, extension, files);
      continue;
    }

    if (entry.isFile() && entry.name.endsWith(extension) && !entry.name.startsWith('_')) {
      files.push(absolutePath);
    }
  }

  return files;
}

async function ensureDirectory(filePath) {
  await fs.mkdir(path.dirname(filePath), { recursive: true });
}

async function compileSass(filePath) {
  const relativePath = path.relative(path.join(sourceDir, 'scss'), filePath);
  const outputPath = path.join(outputRoot, 'css', relativePath).replace(/\.scss$/i, '.css');
  const result = sass.compile(filePath, {
    style: 'compressed',
    sourceMap: true,
    sourceMapIncludeSources: true,
  });

  await ensureDirectory(outputPath);
  const sourceMapFileName = `${path.basename(outputPath)}.map`;
  const cssOutput = `${result.css}\n/*# sourceMappingURL=${sourceMapFileName} */\n`;
  await fs.writeFile(outputPath, cssOutput, 'utf8');

  if (result.sourceMap) {
    await fs.writeFile(`${outputPath}.map`, JSON.stringify(result.sourceMap), 'utf8');
  }

  return outputPath;
}

async function compileJavaScript(filePath) {
  const relativePath = path.relative(path.join(sourceDir, 'js'), filePath);
  const outputPath = path.join(outputRoot, 'js', relativePath);

  await ensureDirectory(outputPath);

  await build({
    entryPoints: [filePath],
    bundle: true,
    outfile: outputPath,
    sourcemap: true,
    minify: true,
    format: 'iife',
    target: ['es2018'],
    platform: 'browser',
    logLevel: 'info',
  });

  return outputPath;
}

async function main() {
  const sassSourceDir = path.join(sourceDir, 'scss');
  const jsSourceDir = path.join(sourceDir, 'js');

  const sassFiles = await collectFiles(sassSourceDir, '.scss');
  const jsFiles = await collectFiles(jsSourceDir, '.js');

  for (const filePath of sassFiles) {
    await compileSass(filePath);
  }

  for (const filePath of jsFiles) {
    await compileJavaScript(filePath);
  }
}


async function watch() {
  await main();

  const watcher = chokidar.watch([
    path.join(sourceDir, 'scss'),
    path.join(sourceDir, 'js')
  ], {
    ignoreInitial: true,
    persistent: true,
  });

  const rebuild = async () => {
    try {
      await main();
    } catch (error) {
      console.error(error);
    }
  };

  watcher.on('add', rebuild);
  watcher.on('change', rebuild);
  watcher.on('unlink', rebuild);

  console.log('Watching src/scss and src/js for changes...');
}

const isWatchMode = process.argv.includes('--watch');

if (isWatchMode) {
  watch().catch((error) => {
    console.error(error);
    process.exitCode = 1;
  });
} else {
  main().catch((error) => {
    console.error(error);
    process.exitCode = 1;
  });
}
