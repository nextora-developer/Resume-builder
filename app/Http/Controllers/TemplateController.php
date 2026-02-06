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

        // ✅ 重点：传跟真实 data 结构一样的假数据
        $data = [
            'name' => 'Your Name',
            'position' => 'Software Engineer',
            'email' => 'you@example.com',
            'phone' => '+60 12-345 6789',
            'location' => 'Kuala Lumpur, MY',

            // ⚠️ preview 没有你 storage 上传的真实头像，所以用一个公开的 placeholder
            // 方案1：你 public 放一张 /images/avatar-demo.jpg
            'avatar_path' => null,
            'avatar_url' => asset('images/avatar-demo.jpg'),

            'links' => [
                'website' => 'https://resume.extech/demo',
                'linkedin' => 'https://linkedin.com/in/yourname',
                'github' => 'https://github.com/yourname',
            ],

            'summary' => 'Short summary goes here...',

            'skills' => ['Laravel', 'MySQL', 'Tailwind', 'Git', 'Docker'],
            'languages' => ['English', 'Chinese', 'Malay'],

            'projects' => [
                [
                    'name' => 'Resume Builder',
                    'role' => 'Full Stack',
                    'link' => 'https://resume.extech/demo',
                    'start' => '2025-07',
                    'end' => '2025-10',
                    'pdf_path' => null, // preview 先不用
                    'highlights' => [
                        'Dynamic form builder + PDF export.',
                        'Template gallery with live previews.',
                    ],
                ],
            ],

            'education' => [
                [
                    'school' => 'Universiti Example',
                    'degree' => 'BSc',
                    'field' => 'Computer Science',
                    'start' => '2019-10',
                    'end' => '2023-07',
                    'notes' => "Dean's List • Final Year Project: Resume Builder",
                ],
            ],

            'experience' => [
                [
                    'company' => 'EXTECH STUDIO',
                    'role' => 'Full Stack Developer',
                    'location' => 'Remote',
                    'start' => '2024-01',
                    'end' => '2025-12',
                    'current' => false,
                    'highlights' => [
                        'Built e-commerce features with Laravel 10 + Tailwind.',
                        'Integrated payment gateway & webhooks.',
                        'Optimized database queries and caching.',
                    ],
                ],
                [
                    'company' => 'BRIF',
                    'role' => 'Backend Engineer',
                    'location' => 'Malaysia',
                    'start' => '2026-01',
                    'end' => null,
                    'current' => true,
                    'highlights' => [
                        'Designed scalable order & voucher system.',
                        'Implemented audit logs and admin tools.',
                    ],
                ],
            ],
        ];

        // ✅ 传一个假的 $resume，避免你模板里还在用 $resume->title / $resume->... 时出问题
        $resume = (object) [
            'title' => null,        // 你现在不要用 title 当 position
            'template' => $slug,
        ];

        return view("resume.templates.$slug", compact('data', 'resume'));
    }
}
