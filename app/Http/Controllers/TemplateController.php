<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = [
            ['slug' => 'classic', 'name' => 'Classic'],
            ['slug' => 'modern', 'name' => 'Modern'],
            ['slug' => 'tech', 'name' => 'Tech'],
        ];

        return view('templates.index', compact('templates'));
    }

    public function preview(string $slug)
    {
        abort_unless(in_array($slug, ['classic', 'modern', 'tech']), 404);

        return view("resume.templates.$slug", [
            'data' => [
                'name' => 'Your Name',
                'title' => 'Your Title',
                'email' => 'you@example.com',
                'summary' => 'Short summary goes here...',
            ],
        ]);
    }
}
