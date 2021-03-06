<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('store_post')) {

    function store_post($title, $datetime, $type, $content) {
        $ci = & get_instance();

        if (!(isset($title)) || !(isset($datetime)) || !(isset($type)) || !(isset($content))) {
            return false;
        }

        //  insert original post data to secondary storage
        $data = array('post_title' => $title, 'post_upvotes' => 0, 'post_downvotes' => 0,
            'post_created' => $datetime, 'user_id' => $ci->session->userdata('user_id'));
        $ci->db->insert("post", $data);

        //  get the post id of the newly stored post
        $ci->db->from('post');
        $ci->db->where('post_title', $title);
        $ci->db->where('post_created', $datetime);
        $ci->db->where('user_id', $ci->session->userdata('user_id'));

        $shared = $ci->db->get()->result();
        if (is_array($shared) && count($shared) == 1 && $type === 'text') {
            //  insert text post data to secondary storage
            $data = array('post_id' => $shared[0]->post_id, 'post_text' => $content);
            return $ci->db->insert("text_post", $data);
        } elseif (is_array($shared) && count($shared) == 1 && $type === 'link') {
            //  insert link post data to secondary storage
            $data = array('post_id' => $shared[0]->post_id, 'post_link' => $content);
            return $ci->db->insert("link_post", $data);
        } else {
            return false;
        }
    }

}

if (!function_exists('get_post')) {

    function get_post($id) {
        //  check the post id
        $ci = & get_instance();
        $ci->db->from('post');
        $ci->db->where('post_id', $id);
        $post = $ci->db->get()->result();

        if (!(is_array($post) && count($post) == 1)) {
            return NULL;
        }

        $ci->db->from('comment');
        $ci->db->where('post_id', $post[0]->post_id);

        $comments = build_comments($ci->db->get()->result());

        $ci->db->from('text_post');
        $ci->db->where('post_id', $post[0]->post_id);
        $text_posts = $ci->db->get()->result();

        if (is_array($text_posts) && count($text_posts) == 1) {
            $text_post = build_text_post($post[0], $text_posts[0]->post_text);
            $text_post->comments = $comments;
            return $text_post;
        } else {
            $ci->db->from('link_post');
            $ci->db->where('post_id', $post[0]->post_id);
            $link_posts = $ci->db->get()->result();

            if (is_array($link_posts) && count($link_posts) == 1) {
                $link_post = build_link_post($post[0], $link_posts[0]->post_link);
                $link_post->comments = $comments;
                return $link_post;
            } else {
                return NULL;
            }
        }
    }

}

if (!function_exists('get_posts')) {

    function get_posts($limit, $offset) {
        //  check the post id
        $ci = & get_instance();
        
        $query = "post.*, (`post_upvotes` - `post_downvotes`) AS `difference`";
        
        $ci->db->select($query)->from('post');
        $ci->db->order_by('post_created', 'DESC');
        $ci->db->order_by('difference', 'DESC');
        $ci->db->limit($limit, $offset);
        $results = $ci->db->get();
        
//        $results = $ci->db->get('post', $limit, $offset);
        $posts = array();

        foreach ($results->result() as $post) {
            $ci->db->from('comment');
            $ci->db->where('post_id', $post->post_id);

            $comments = build_comments($ci->db->get()->result());

            $ci->db->from('text_post');
            $ci->db->where('post_id', $post->post_id);
            $text_posts = $ci->db->get()->result();

            if (is_array($text_posts) && count($text_posts) == 1) {
                $text_post = build_text_post($post, $text_posts[0]->post_text);
                $text_post->comments = $comments;
                $posts[] = array($text_post);
            } else {
                $ci->db->from('link_post');
                $ci->db->where('post_id', $post->post_id);
                $link_posts = $ci->db->get()->result();

                if (is_array($link_posts) && count($link_posts) == 1) {
                    $link_post = build_link_post($post, $link_posts[0]->post_link);
                    $link_post->comments = $comments;
                    $posts[] = array($link_post);
                }
            }
        }

        return $posts;
    }

}

if (!function_exists('get_post_count')) {
    function get_post_count() {
        $ci = & get_instance();
        return $ci->db->count_all("post");
    }
}

if (!function_exists('vote_post')) {

    function vote_post($id, $votes, $type) {
        if (!(isset($id) && isset($votes))) {
            return;
        }

        $ci = & get_instance();
        if ($type == 'up') {
            $data = array('post_upvotes' => $votes);
        } else {
            $data = array('post_downvotes' => $votes);
        }

        $ci->db->where('post_id', $id);
        $ci->db->update('post', $data);
    }

}

if (!function_exists('delete_post')) {

    function delete_post($post_id, $type) {
        $ci = & get_instance();

        if (isset($post_id) && isset($type)) {
            //  delete comments
            $ci->db->delete('comment', array('post_id' => $post_id));

            //  delete sub-class post references
            if ($type === 'text') {
                $ci->db->delete('text_post', array('post_id' => $post_id));
            } else if ($type === 'link') {
                $ci->db->delete('link_post', array('post_id' => $post_id));
            }

            //  delete original post reference
            return $ci->db->delete('post', array('post_id' => $post_id));
        } else {
            return false;
        }
    }

}

if (!function_exists('store_comment')) {

    function store_comment($text, $datetime, $parent, $post_id) {
        $ci = & get_instance();
        //  insert data to secondary storage
        $data = array('comment_text' => $text, 'comment_datetime' => $datetime, 'comment_parent_id' => $parent,
            'post_id' => $post_id);
        $ci->db->insert("comment", $data);
    }

}

if (!function_exists('get_comment')) {

    function get_comment($text, $datetime, $post_id) {
        if (!(isset($text) && isset($datetime) && (isset($post_id)))) {
            return NULL;
        }

        $ci = & get_instance();
        //  get the post id of the newly stored post
        $ci->db->from('comment');
        $ci->db->where('comment_text', $text);
        $ci->db->where('comment_datetime', $datetime);
        $ci->db->where('post_id', $post_id);
        $result = $ci->db->get()->result();

        if (is_array($result) && count($result) == 1) {
            return build_comment($result[0]);
        } else {
            return NULL;
        }
    }

}

if (!function_exists('delete_comment')) {

    function delete_comment($comment_id) {
        $ci = & get_instance();

        if (isset($comment_id)) {
            $ci->db->delete('comment', array('comment_id' => $comment_id));
        }
    }

}

function build_comments($dbRecords) {
    $comments = array();

    foreach ($dbRecords as $record) {
        $comments[] = build_comment($record);
    }

    return $comments;
}

function build_comment($record) {
    $comment = new Comment();
    $comment->id = $record->comment_id;
    $comment->text = $record->comment_text;
    $comment->creation = date($record->comment_datetime);
    $comment->parent_id = $record->comment_parent_id;

    return $comment;
}

function build_text_post($dbRecord, $text) {
    $post = new Text();

    $post->id = $dbRecord->post_id;
    $post->title = $dbRecord->post_title;
    $post->upvotes = $dbRecord->post_upvotes;
    $post->downvotes = $dbRecord->post_downvotes;
    $post->creation = $dbRecord->post_created;
    $post->text = $text;

    return $post;
}

function build_link_post($dbRecord, $link) {
    $post = new Link();

    $post->id = $dbRecord->post_id;
    $post->title = $dbRecord->post_title;
    $post->upvotes = $dbRecord->post_upvotes;
    $post->downvotes = $dbRecord->post_downvotes;
    $post->creation = $dbRecord->post_created;
    $post->link = $link;

    return $post;
}
