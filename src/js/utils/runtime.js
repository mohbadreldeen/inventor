export function onDomReady(callback) {
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", callback, { once: true });
		return;
	}

	callback();
}

export function isBuilderMode() {
	if (window.elementorFrontend && typeof window.elementorFrontend.isEditMode === "function" && window.elementorFrontend.isEditMode()) {
		return true;
	}

	try {
		if (window.top && window.top !== window && window.top.document && window.top.document.body) {
			if (window.top.document.body.classList.contains("elementor-editor-active") || window.top.document.body.classList.contains("elementor-editor-preview")) {
				return true;
			}
		}
	} catch (error) {
		// Ignore cross-window access issues and fall back to local checks.
	}

	return !!(document.body && (document.body.classList.contains("elementor-editor-active") || document.body.classList.contains("elementor-editor-preview")));
}

export function onElementorReady(callback) {
	if (!window.elementorFrontend || !window.elementorFrontend.hooks) {
		return;
	}

	window.elementorFrontend.hooks.addAction("frontend/element_ready/global", function ($scope) {
		callback($scope && $scope[0] ? $scope[0] : document);
	});
}
