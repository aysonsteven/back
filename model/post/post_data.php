<?php
/**
 * @see README.md
 */
namespace model\post;
use model\file\File;

class Post_Data extends Post {
    public function __construct()
    {
        parent::__construct();
        $this->setTable('post_data');
    }




    /**
     *
     * Returns sanitized post_data record to user.
     *
     * @note this method works on '$this'
     *
     * @param array $option
     * @return array
     */
    public function pre( $option = [] ) {
        $record = $this->getRecord();

        // delete security data.
        unset( $record['password'], $record['user_agent'] );




        //
        if ( $option && isset($option['extra']) && $option['extra'] ) {
            if ( isset($option['extra']['meta']) && $option['extra']['meta'] ) $record['meta'] = $this->meta()->get();
            if ( isset($option['extra']['file']) && $option['extra']['file'] ) $record['files'] = $this->file()->get();
        }
        

        // debug_log($record);
        return $record;
    }

    /**
     *
     * This does not delete the record. but set it empty.
     *
     *
     * Overrides the entity()->delete() NOT to delete the record but to SET empty title and content.
     * @warning This does not delete the record.
     *
     * @return bool|number - same as entity()->delete()
     *
     */
    public function delete() {

        if ( $this->deleted() ) return ERROR_ALREADY_DELETED;
        $record_copy = $this->getRecord();
        $re = $this->update([
            'deleted' => '1',
            'Supplier' => '',
            'TIN' => ''
        ]);
        if ( is_success($re) ) {
            db()->insert( table_post_deleted(), $record_copy );
        }

        return $re;
    }




    /**
     *
     * Returns an array of post after sanitizing.
     *
     * @param $records - array of post records.
     *
     * @param array $option
     * @return array
     */
    public function pres( & $records, $option = [] ) {
        $new_records = [];
        if ( empty( $records ) ) return $new_records;
        foreach( $records as $record ) {
            $new_records[] = post()->reset( $record )->pre( $option );
        }
        return $new_records;
    }

    public function getConfigs( & $records ) {
        if ( empty( $records ) ) return [];

        $configs = [];
        foreach( $records as $post ) {
            if ( isset($post['post_config_idx']) ) {
                $config = config( $post['post_config_idx'] );
                if ( $config->exist() ) $configs[ $config->id ] = $config->getRecord();
            }
        }
        return $configs;

    }

    /**
     * Returns a string as title of the post
     *
     * @note If there is no title, it gets title from the first part of content.
     * @note it strips off HTML tags.
     * @note it returns less than 250 characters.
     *
     * @return mixed|null|string
     */
    public function getSafeTitle()
    {
        $title = $this->title;
        if ( empty( $title ) ) $title = $this->content;
        $title = strip_tags( $title );
        $title = strcut( $title, 250 );
        return $title;
    }

    /**
     * Returns a safe content just like title.
     *
     * @note if there is no content, it returns title.
     *
     */
    public function getSafeContent()
    {
        $content = $this->content;
        if ( empty( $content ) ) $content = $this->title;
        $content = strip_tags( $content );
        return $content;
    }

    /**
     * @return \model\user\User
     *
     * @code
     *          $author = $this->post_data->getUser()->getSafeName();
     * @endcode
     */
    public function getUser()
    {
        return user( $this->user_idx );
    }


}
