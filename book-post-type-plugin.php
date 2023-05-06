<?php
/*
Plugin Name: Books-post-type
Description: register a post type "Books" and custom taxonomies "Author"
and "Genre" that will allow the book store to easily categorize and search for books
based on author and genre.
Version: 1.0.0
Author: Hossein Shahidi
License: GPLv2 or later
Text Domain: book-post-type-plugin
*/

// Register Books post type
function book_register_post_type() {
    $labels = array(
        'name'               => __( 'Books', 'book-post-type-plugin' ),
        'singular_name'      => __( 'Book', 'book-post-type-plugin' ),
        'menu_name'          => __( 'Books', 'book-post-type-plugin' ),
        'add_new'            => __( 'Add New', 'book-post-type-plugin' ),
        'add_new_item'       => __( 'Add New Book', 'book-post-type-plugin' ),
        'edit'               => __( 'Edit', 'book-post-type-plugin' ),
        'edit_item'          => __( 'Edit Book', 'book-post-type-plugin' ),
        'new_item'           => __( 'New Book', 'book-post-type-plugin' ),
        'view'               => __( 'View', 'book-post-type-plugin' ),
        'view_item'          => __( 'View Book', 'book-post-type-plugin' ),
        'search_items'       => __( 'Search Books', 'book-post-type-plugin' ),
        'not_found'          => __( 'No books found', 'book-post-type-plugin' ),
        'not_found_in_trash' => __( 'No books found in trash', 'book-post-type-plugin' )
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'books' ),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' )
    );

    register_post_type( 'book', $args );
}
add_action( 'init', 'book_register_post_type' );

// Register Genre taxonomy
function register_genre_taxonomy() {
    $genre_labels = array(
        'name'                       => __( 'Genres', 'book-post-type-plugin' ),
        'singular_name'              => __( 'Genre', 'book-post-type-plugin' ),
        'menu_name'                  => __( 'Genres', 'book-post-type-plugin' ),
        'search_items'               => __( 'Search Genres', 'book-post-type-plugin' ),
        'popular_items'              => __( 'Popular Genres', 'book-post-type-plugin' ),
        'all_items'                  => __( 'All Genres', 'book-post-type-plugin' ),
        'edit_item'                  => __( 'Edit Genre', 'book-post-type-plugin' ),
        'update_item'                => __( 'Update Genre', 'book-post-type-plugin' ),
        'add_new_item'               => __( 'Add New Genre', 'book-post-type-plugin' ),
        'new_item_name'              => __( 'New Genre Name', 'book-post-type-plugin' ),
        'separate_items_with_commas' => __( 'Separate genres with commas', 'book-post-type-plugin' ),
        'add_or_remove_items'        => __( 'Add or remove genres', 'book-post-type-plugin' ),
    );

    $genre_args = array(
        'labels'            => $genre_labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'genre' ),
    );

    register_taxonomy( 'genre', 'book', $genre_args );
}
add_action( 'init', 'register_genre_taxonomy' );

// Register Author taxonomy
function register_author_taxonomy() {
    $author_labels = array(
        'name'                       => __( 'Authors', 'book-post-type-plugin' ),
        'singular_name'              => __( 'Author', 'book-post-type-plugin' ),
        'menu_name'                  => __( 'Authors', 'book-post-type-plugin' ),
        'search_items'               => __( 'Search Authors', 'book-post-type-plugin' ),
        'popular_items'              => __( 'Popular Authors', 'book-post-type-plugin' ),
        'all_items'                  => __( 'All Authors', 'book-post-type-plugin' ),
        'edit_item'                  => __( 'Edit Author', 'book-post-type-plugin' ),
        'update_item'                => __( 'Update Author', 'book-post-type-plugin' ),
        'add_new_item'               => __( 'Add New Author', 'book-post-type-plugin' ),
        'new_item_name'              => __( 'New Author Name', 'book-post-type-plugin' ),
        'separate_items_with_commas' => __( 'Separate authors with commas', 'book-post-type-plugin' ),
        'add_or_remove_items'        => __( 'Add or remove authors', 'book-post-type-plugin' ),
    );

    $author_args = array(
        'labels'            => $author_labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'author' ),
    );

    register_taxonomy( 'author', 'book', $author_args );
}
add_action( 'init', 'register_author_taxonomy' );

// Add custom meta boxes
function add_custom_meta_boxes() {
    add_meta_box( 'book_author', __( 'Author Name', 'book-post-type-plugin' ), 'render_author_meta_box', 'book', 'normal', 'default' );
    add_meta_box( 'book_isbn', __( 'ISBN', 'book-post-type-plugin' ), 'render_isbn_meta_box', 'book', 'normal', 'default' );
    add_meta_box( 'book_price', __( 'Price', 'book-post-type-plugin' ), 'render_price_meta_box', 'book', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'add_custom_meta_boxes' );

// Render Author Name meta box
function render_author_meta_box( $post ) {
    $author = get_post_meta( $post->ID, 'book_author', true );
    ?>
    <label for="book-author">Author:</label>
    <input type="text" id="book-author" name="book_author" value="<?php echo esc_attr( $author ); ?>">
    <?php
}
// Render ISBN meta box
function render_isbn_meta_box( $post ) {
    $isbn = get_post_meta( $post->ID, 'book_isbn', true );
    ?>
    <label for="book-isbn">ISBN:</label>
    <input type="text" id="book-isbn" name="book_isbn" value="<?php echo esc_attr( $isbn ); ?>">
    <?php
}

// Render Price meta box
function render_price_meta_box( $post ) {
    $price = get_post_meta( $post->ID, 'book_price', true );
    ?>
    <label for="book-price">Price:</label>
    <input type="text" id="book-price" name="book_price" value="<?php echo esc_attr( $price ); ?>">
    <?php
}

// Save custom meta box values
function save_custom_meta_boxes( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['book_author'] ) ) {
        update_post_meta( $post_id, 'book_author', sanitize_text_field( $_POST['book_author'] ) );
    }

    if ( isset( $_POST['book_isbn'] ) ) {
        update_post_meta( $post_id, 'book_isbn', sanitize_text_field( $_POST['book_isbn'] ) );
    }

    if ( isset( $_POST['book_price'] ) ) {
        update_post_meta( $post_id, 'book_price', sanitize_text_field( $_POST['book_price'] ) );
    }
}
add_action( 'save_post', 'save_custom_meta_boxes' );