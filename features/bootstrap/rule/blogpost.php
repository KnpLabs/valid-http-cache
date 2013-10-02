<?php

use Valid\Rule;

class BlogPost implements Rule\ETag, Rule\LastModified
{
    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'app_blogpost_show';
    }

    public function getETag(Request $request)
    {
        // called after resource revolver: we have access to resolved controller args

        return md5($request->attributes->get('blogPost')->getUpdatedAt()->format('U'));
    }

    public function getLastModified(Request $request)
    {
        // called after resource revolver: we have access to resolved controller args

        return $request->attributes->get('blogPost')->getUpdatedAt();
    }
}
