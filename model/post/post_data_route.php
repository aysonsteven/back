<?php

$_optional = [
    'password', 'Supplier', 'TIN', 'parent_idx', 'supplier',
    'name', 'middle_name', 'last_name', 'email', 'gender', 'birthdate', 'mobile', 'landline', 'contact',
    'country', 'province', 'city', 'address', 'AmountOfPurchase', 'InputTax', 'AmountDue', 'TotalAmountOfPurchase',
    'TotalInputTax', 'TotalAmountDue', 'month', 'year', 'InvoiceNo'
];

add_route( 'post_data.create', [
    'path' => "\\model\\post\\post_data_interface",
    "method" => "create",
    "variables" => [
        'required' => [ 'post_config_id' ],
        'optional' => $_optional,
        'system' => [ 'session_id', 'post_config_id', 'file_hooks' ]
    ],
    'validator' => function () {
        $config = config()->load( in( 'post_config_id' ) );
        if( ! $config->exist() ) return ERROR_POST_CONFIG_NOT_EXIST;
        return OK;
    }
]);


add_route('post_data.install',[
    'path' => "\\model\\post\\post_install"
]);

add_route( 'post_data.edit', [
    'path' => "\\model\\post\\post_data_interface",
    "method" => "edit",
    "variables" => [
        'required' => [ 'idx' ],
        'optional' => $_optional,
        'system' => [ 'session_id', 'file_hooks' ]
    ],
    'validator' => function () {

        if( strlen( in('Supplier') ) > 254 ) return ERROR_TITLE_TOO_LONG;
        $post = post( in('idx') );
        if ( is_error( $post ) ) return $post;
        if ( ! $post->exist() ) return ERROR_POST_NOT_EXIST;


        $config = config()->load( $post->post_config_idx );
        if ( ! $config->exist() ) return ERROR_POST_CONFIG_NOT_EXIST;


        if ( $re = $post->editPermission() ) return $re;

        return [ $post, $config ];
    }
]);


add_route( 'post_data.delete', [
    'path' => "\\model\\post\\post_data_interface",
    "method" => "delete",
    "variables" => [
        'required' => [ 'idx' ],
        'optional' => [ 'session_id', 'password' ],
        'system' => []
    ],
    'validator' => function() {
        $post = post( in('idx') );
        if ( is_error( $post ) ) return $post;
        if ( ! $post->exist() ) return ERROR_POST_NOT_EXIST;

        $config = config()->load( $post->post_config_idx );
        if ( ! $config->exist() ) return ERROR_POST_CONFIG_NOT_EXIST;

        if ( $re = $post->deletePermission() ) return $re;
        return [ $post, $config ];
    }
]);


add_route( 'post_data.data', [
    'path' => "\\model\\post\\post_data_interface",
    "method" => "data",
    "variables" => [
        'required' => [ 'idx' ],
        'optional' => [ ],
        'system' => []
    ]
]);

add_route( 'post_data.list', [
    'path' => "\\model\\post\\post_data_interface",
    "method" => "search",
    'variables' => [
        'required' => [],
        'optional' => [ 'from', 'limit', 'where', 'bind', 'order', 'select', 'extra' ],
        'system' => [ 'session_id' ]
    ]
]);


add_route( 'post.test', [
    'path' => "\\model\\post\\post_data_test",
    'method' => 'single_test'
]);



route()->add( 'post.test.create', [
    'path' => "\\model\\post\\post_data_test",
    'method' => 'create_test_data'
]);