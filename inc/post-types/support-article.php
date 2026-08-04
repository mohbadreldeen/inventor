<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'init', 'wp_inventor_register_support_article_post_type' );
add_action( 'init', 'wp_inventor_register_support_article_category_taxonomy' );
add_action( 'add_meta_boxes', 'wp_inventor_support_article_add_icon_meta_box' );
add_action( 'save_post_support-article', 'wp_inventor_support_article_save_icon_meta' );
add_action( 'support-article-category_add_form_fields', 'wp_inventor_support_article_category_add_image_field' );
add_action( 'support-article-category_edit_form_fields', 'wp_inventor_support_article_category_edit_image_field' );
add_action( 'created_support-article-category', 'wp_inventor_support_article_category_save_image_field' );
add_action( 'edited_support-article-category', 'wp_inventor_support_article_category_save_image_field' );
add_action( 'admin_enqueue_scripts', 'wp_inventor_support_article_admin_assets' );

function wp_inventor_register_support_article_post_type() {
	$labels = array(
		'name'                  => __( 'Support Articles', 'hello-elementor-child' ),
		'singular_name'         => __( 'Support Article', 'hello-elementor-child' ),
		'menu_name'             => __( 'Support Articles', 'hello-elementor-child' ),
		'name_admin_bar'        => __( 'Support Article', 'hello-elementor-child' ),
		'add_new'               => __( 'Add New', 'hello-elementor-child' ),
		'add_new_item'          => __( 'Add New Support Article', 'hello-elementor-child' ),
		'new_item'              => __( 'New Support Article', 'hello-elementor-child' ),
		'edit_item'             => __( 'Edit Support Article', 'hello-elementor-child' ),
		'view_item'             => __( 'View Support Article', 'hello-elementor-child' ),
		'all_items'             => __( 'All Support Articles', 'hello-elementor-child' ),
		'search_items'          => __( 'Search Support Articles', 'hello-elementor-child' ),
		'parent_item_colon'     => __( 'Parent Support Articles:', 'hello-elementor-child' ),
		'not_found'             => __( 'No support articles found.', 'hello-elementor-child' ),
		'not_found_in_trash'    => __( 'No support articles found in Trash.', 'hello-elementor-child' ),
		'featured_image'        => __( 'Support Article Featured Image', 'hello-elementor-child' ),
		'set_featured_image'    => __( 'Set featured image', 'hello-elementor-child' ),
		'remove_featured_image' => __( 'Remove featured image', 'hello-elementor-child' ),
		'use_featured_image'    => __( 'Use as featured image', 'hello-elementor-child' ),
		'archives'              => __( 'Support Articles Archives', 'hello-elementor-child' ),
		'insert_into_item'      => __( 'Insert into support article', 'hello-elementor-child' ),
		'uploaded_to_this_item' => __( 'Uploaded to this support article', 'hello-elementor-child' ),
		'filter_items_list'     => __( 'Filter support articles list', 'hello-elementor-child' ),
		'items_list_navigation' => __( 'Support articles list navigation', 'hello-elementor-child' ),
		'items_list'            => __( 'Support articles list', 'hello-elementor-child' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'has_archive'        => true,
		'rewrite'            => array( 'slug' => 'support-articles', 'with_front' => false ),
		'menu_icon'          => 'dashicons-sos',
		'supports'           => array( 'title', 'editor', 'author', 'excerpt', 'thumbnail', 'comments', 'revisions', 'elementor' ),
		'capability_type'    => 'post',
		'query_var'          => true,
		'taxonomies'         => array( 'support-article-category' ),
	);

	register_post_type( 'support-article', $args );
}

function wp_inventor_register_support_article_category_taxonomy() {
	$labels = array(
		'name'              => __( 'Support Article Categories', 'hello-elementor-child' ),
		'singular_name'     => __( 'Support Article Category', 'hello-elementor-child' ),
		'search_items'      => __( 'Search Support Article Categories', 'hello-elementor-child' ),
		'all_items'         => __( 'All Support Article Categories', 'hello-elementor-child' ),
		'parent_item'       => __( 'Parent Support Article Category', 'hello-elementor-child' ),
		'parent_item_colon' => __( 'Parent Support Article Category:', 'hello-elementor-child' ),
		'edit_item'         => __( 'Edit Support Article Category', 'hello-elementor-child' ),
		'update_item'       => __( 'Update Support Article Category', 'hello-elementor-child' ),
		'add_new_item'      => __( 'Add New Support Article Category', 'hello-elementor-child' ),
		'new_item_name'     => __( 'New Support Article Category Name', 'hello-elementor-child' ),
		'menu_name'         => __( 'Categories', 'hello-elementor-child' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'support-article-category', 'with_front' => false ),
	);

	register_taxonomy( 'support-article-category', array( 'support-article' ), $args );
}

function wp_inventor_support_article_add_icon_meta_box() {
	add_meta_box(
		'inventor-support-article-icon',
		__( 'Support Article Icon', 'hello-elementor-child' ),
		'wp_inventor_support_article_render_icon_meta_box',
		'support-article',
		'side',
		'default'
	);
}

function wp_inventor_support_article_render_icon_meta_box( $post ) {
	$icon_id  = (int) get_post_meta( $post->ID, '_wp_inventor_support_article_icon_id', true );
	$icon_url = $icon_id ? wp_get_attachment_image_url( $icon_id, 'thumbnail' ) : '';

	wp_nonce_field( 'wp_inventor_support_article_icon_meta', 'wp_inventor_support_article_icon_meta_nonce' );
	?>
	<p><?php esc_html_e( 'Choose an icon image for this support article.', 'hello-elementor-child' ); ?></p>
	<input type="hidden" id="inventor-support-article-icon-id" name="wp_inventor_support_article_icon_id" value="<?php echo esc_attr( $icon_id ); ?>" />
	<div id="inventor-support-article-icon-preview" style="margin-bottom:10px;">
		<?php if ( $icon_url ) : ?>
			<img src="<?php echo esc_url( $icon_url ); ?>" alt="" style="max-width:100%;height:auto;" />
		<?php endif; ?>
	</div>
	<button type="button" class="button" id="inventor-support-article-icon-select"><?php esc_html_e( 'Select Icon', 'hello-elementor-child' ); ?></button>
	<button type="button" class="button" id="inventor-support-article-icon-remove" <?php echo $icon_url ? '' : 'style="display:none;"'; ?>><?php esc_html_e( 'Remove Icon', 'hello-elementor-child' ); ?></button>
	<?php
}

function wp_inventor_support_article_save_icon_meta( $post_id ) {
	if ( ! isset( $_POST['wp_inventor_support_article_icon_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wp_inventor_support_article_icon_meta_nonce'] ) ), 'wp_inventor_support_article_icon_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['wp_inventor_support_article_icon_id'] ) ) {
		$icon_id = absint( wp_unslash( $_POST['wp_inventor_support_article_icon_id'] ) );

		if ( $icon_id > 0 ) {
			update_post_meta( $post_id, '_wp_inventor_support_article_icon_id', $icon_id );
		} else {
			delete_post_meta( $post_id, '_wp_inventor_support_article_icon_id' );
		}
	}
}

function wp_inventor_support_article_category_add_image_field() {
	?>
	<div class="form-field term-group">
		<label for="inventor-support-article-category-image-id"><?php esc_html_e( 'Category Image', 'hello-elementor-child' ); ?></label>
		<input type="hidden" id="inventor-support-article-category-image-id" name="wp_inventor_support_article_category_image_id" value="" />
		<div id="inventor-support-article-category-image-preview" style="margin: 8px 0;"></div>
		<button type="button" class="button" id="inventor-support-article-category-image-select"><?php esc_html_e( 'Select Image', 'hello-elementor-child' ); ?></button>
		<button type="button" class="button" id="inventor-support-article-category-image-remove" style="display:none;"><?php esc_html_e( 'Remove Image', 'hello-elementor-child' ); ?></button>
		<p class="description"><?php esc_html_e( 'Choose an image for this support article category.', 'hello-elementor-child' ); ?></p>
	</div>
	<?php
}

function wp_inventor_support_article_category_edit_image_field( $term ) {
	$image_id  = (int) get_term_meta( $term->term_id, '_wp_inventor_support_article_category_image_id', true );
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : '';
	?>
	<tr class="form-field term-group-wrap">
		<th scope="row"><label for="inventor-support-article-category-image-id"><?php esc_html_e( 'Category Image', 'hello-elementor-child' ); ?></label></th>
		<td>
			<input type="hidden" id="inventor-support-article-category-image-id" name="wp_inventor_support_article_category_image_id" value="<?php echo esc_attr( $image_id ); ?>" />
			<div id="inventor-support-article-category-image-preview" style="margin: 8px 0;">
				<?php if ( $image_url ) : ?>
					<img src="<?php echo esc_url( $image_url ); ?>" alt="" style="max-width:100%;height:auto;" />
				<?php endif; ?>
			</div>
			<button type="button" class="button" id="inventor-support-article-category-image-select"><?php esc_html_e( 'Select Image', 'hello-elementor-child' ); ?></button>
			<button type="button" class="button" id="inventor-support-article-category-image-remove" <?php echo $image_url ? '' : 'style="display:none;"'; ?>><?php esc_html_e( 'Remove Image', 'hello-elementor-child' ); ?></button>
			<p class="description"><?php esc_html_e( 'Choose an image for this support article category.', 'hello-elementor-child' ); ?></p>
		</td>
	</tr>
	<?php
}

function wp_inventor_support_article_category_save_image_field( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	if ( isset( $_POST['wp_inventor_support_article_category_image_id'] ) ) {
		$image_id = absint( wp_unslash( $_POST['wp_inventor_support_article_category_image_id'] ) );

		if ( $image_id > 0 ) {
			update_term_meta( $term_id, '_wp_inventor_support_article_category_image_id', $image_id );
		} else {
			delete_term_meta( $term_id, '_wp_inventor_support_article_category_image_id' );
		}
	}
}

function wp_inventor_support_article_admin_assets( $hook_suffix ) {
	$screen = get_current_screen();
	if ( ! $screen ) {
		return;
	}

	$is_support_article_post_screen = in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) && 'support-article' === $screen->post_type;
	$is_support_article_term_screen = in_array( $hook_suffix, array( 'edit-tags.php', 'term.php' ), true ) && isset( $screen->taxonomy ) && 'support-article-category' === $screen->taxonomy;

	if ( ! $is_support_article_post_screen && ! $is_support_article_term_screen ) {
		return;
	}

	wp_enqueue_media();
	wp_add_inline_script(
		'jquery-core',
		"jQuery(function($){
			function bindMediaPicker(selectButton, removeButton, idField, previewWrap, title, buttonText) {
				if (!selectButton.length || !removeButton.length || !idField.length || !previewWrap.length) {
					return;
				}

				var frame;

				selectButton.on('click', function(e){
					e.preventDefault();

					if (frame) {
						frame.open();
						return;
					}

					frame = wp.media({
						title: title,
						button: { text: buttonText },
						multiple: false,
						library: { type: 'image' }
					});

					frame.on('select', function(){
						var attachment = frame.state().get('selection').first().toJSON();
						idField.val(attachment.id);
						previewWrap.html('<img src=\"' + attachment.url + '\" alt=\"\" style=\"max-width:100%;height:auto;\" />');
						removeButton.show();
					});

					frame.open();
				});

				removeButton.on('click', function(e){
					e.preventDefault();
					idField.val('');
					previewWrap.html('');
					removeButton.hide();
				});
			}

			bindMediaPicker(
				$('#inventor-support-article-icon-select'),
				$('#inventor-support-article-icon-remove'),
				$('#inventor-support-article-icon-id'),
				$('#inventor-support-article-icon-preview'),
				'Select Icon',
				'Use Icon'
			);

			bindMediaPicker(
				$('#inventor-support-article-category-image-select'),
				$('#inventor-support-article-category-image-remove'),
				$('#inventor-support-article-category-image-id'),
				$('#inventor-support-article-category-image-preview'),
				'Select Category Image',
				'Use Image'
			);
		});"
	);
}

function wp_inventor_support_article_flush_rewrite_rules() {
	wp_inventor_register_support_article_post_type();
	wp_inventor_register_support_article_category_taxonomy();
	flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'wp_inventor_support_article_flush_rewrite_rules' );

