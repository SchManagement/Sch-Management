<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Lead;
use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_projects' => Project::query()->count(),
                'published_projects' => Project::published()->count(),
                'new_leads' => Lead::withStatus(LeadStatus::New)->count(),
                'published_articles' => Article::published()->count(),
            ],
            'recentLeads' => Lead::query()
                ->latest()
                ->limit(5)
                ->get(['id', 'name', 'company', 'project_type', 'status', 'created_at']),
            'latestProjects' => Project::query()
                ->with('category:id,name')
                ->latest()
                ->limit(5)
                ->get(['id', 'title', 'slug', 'category_id', 'status', 'updated_at']),
            'latestArticles' => Article::query()
                ->with('category:id,name')
                ->latest()
                ->limit(5)
                ->get(['id', 'title', 'slug', 'category_id', 'status', 'updated_at']),
        ]);
    }
}
