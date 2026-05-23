<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;

class SearchService{
    public function search($q){
        return [
            'posts' => Post::where('title', 'like', "%$q%")->get(),
            'users' => User::where('name', 'like', "%$q%")->get()
        ];
    }
}
?>