<?php
/**
 * @see README.md
 */
namespace model\post;


class Post_Install extends Post {
    public function __construct()
    {
        
// create_post_data_table( DATABASE_PREFIX . 'post_data' );
// create_post_data_table( DATABASE_PREFIX . 'post_data_deleted' );
// $_table_name = DATABASE_PREFIX . 'post_data';
$_table_name = DATABASE_PREFIX . 'post_data_deleted';
    db()
        ->dropTable( $_table_name )
        ->createTable( $_table_name )
        ->add('root_idx', 'INT UNSIGNED DEFAULT 0')
        ->add('parent_idx', 'INT UNSIGNED DEFAULT 0')
        ->add('order_no', 'INT UNSIGNED DEFAULT 0')
        ->add('depth', 'INT UNSIGNED DEFAULT 0')
        ->add('user_idx', 'INT UNSIGNED DEFAULT 0')
        ->add('post_config_idx', 'INT UNSIGNED DEFAULT 0')
        ->add('content', 'LONGTEXT')
        ->add('password', 'varchar')
        ->add('secret', 'char', 1)
        ->add('deleted', 'char', 1)
        ->add('title', 'varchar', 256)

        ->add('ip', 'varchar', 32)
        ->add('user_agent', 'varchar', 255)

        ->add('name', 'VARCHAR', 254)
        ->add('middle_name', 'VARCHAR', 254)
        ->add('last_name', 'VARCHAR', 254)
        ->add('email', 'VARCHAR', 254)
        ->add('gender', 'VARCHAR', 254)
        ->add('birthdate', 'INT UNSIGNED DEFAULT 0')
        ->add('mobile', 'VARCHAR', 254)
        ->add('landline', 'VARCHAR', 254)
        ->add('contact', 'VARCHAR', 254)

        ->add('country', 'VARCHAR', 64)
        ->add('province', 'VARCHAR', 256)
        ->add('city', 'VARCHAR', 256)
        ->add('address', 'VARCHAR', 256)
        ->index( 'user_idx' )
        ->index( 'post_config_idx' )
        ->index( 'post_config_idx,user_idx' );
    die_if_table_not_exist( $_table_name );
    }
}