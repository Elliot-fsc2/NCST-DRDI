<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::new-layout')] class extends Component {
    public $financialStats = [];
    public $programSummaries = [];
    public $recentActivities = [];

    public function mount(): void
    {
        $this->financialStats = [
            'total_collectibles' => 150250.00,
            'total_expenses' => 45000.00,
            'net_savings' => 105250.00,
        ];

        $this->programSummaries = [
            ['id' => 1, 'name' => 'BS Computer Science', 'count' => 45, 'status' => 'On-track'],
            ['id' => 2, 'name' => 'BS Information Tech', 'count' => 38, 'status' => 'Pending'],
            ['id' => 3, 'name' => 'BS Engineering', 'count' => 22, 'status' => 'On-track'],
        ];

        $this->recentActivities = [
            [
                'id' => 1,
                'title' => 'Honorarium Computed',
                'description' => 'Calculations completed for DRDI Personnel.',
                'time' => now()->subMinutes(30),
            ],
        ];
    }
};
?>

<x-slot name="title">RDO Dashboard</x-slot>

<x-slot name="breadcrumbs">
    <ol class="flex flex-wrap items-center gap-1 text-sm">
        <li class="font-bold text-primary">Admin</li>
        <li class="text-on-surface/50">/</li>
        <li class="font-bold text-on-surface-strong" aria-current="page">Dashboard</li>
    </ol>
</x-slot>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <div class="lg:col-span-3 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-outline p-5 rounded-radius shadow-sm">
                <p class="text-xs font-medium text-on-surface/60 uppercase">Total Collectibles</p>
                <h3 class="text-2xl font-bold text-primary mt-1">₱{{ number_format($financialStats['total_collectibles'], 2) }}</h3>
            </div>
            <div class="bg-white border border-outline p-5 rounded-radius shadow-sm">
                <p class="text-xs font-medium text-on-surface/60 uppercase">Total Expenses</p>
                <h3 class="text-2xl font-bold text-error mt-1">₱{{ number_format($financialStats['total_expenses'], 2) }}</h3>
            </div>
            <div class="bg-white border border-outline p-5 rounded-radius shadow-sm">
                <p class="text-xs font-medium text-on-surface/60 uppercase">Net Savings</p>
                <h3 class="text-2xl font-bold text-success mt-1">₱{{ number_format($financialStats['net_savings'], 2) }}</h3>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-bold text-on-surface-strong mb-4">Activity Feed</h2>
            <div class="space-y-4">
                @foreach($recentActivities as $activity)
                    <article class="bg-white border border-outline rounded-radius p-5">
                        <div class="flex justify-between">
                            <div>
                                <h3 class="font-semibold text-on-surface-strong">{{ $activity['title'] }}</h3>
                                <p class="text-sm text-on-surface mt-1">{{ $activity['description'] }}</p>
                            </div>
                            <time class="text-xs text-on-surface/50">{{ $activity['time']->diffForHumans() }}</time>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>

    <aside class="lg:col-span-1 space-y-4">
        <div class="bg-white border border-outline rounded-radius p-4">
            <h2 class="text-lg font-semibold text-on-surface-strong mb-4">Master List Overview</h2>
            <div class="space-y-3">
                @foreach($programSummaries as $program)
                    <div class="p-3 border border-outline/30 rounded-radius">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-bold truncate">{{ $program['name'] }}</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-primary/10 text-primary font-medium">
                                {{ $program['status'] }}
                            </span>
                        </div>
                        <div class="flex justify-between text-xs text-on-surface/60">
                            <span>Students</span>
                            <span class="font-bold">{{ $program['count'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button class="w-full py-3 text-sm font-bold bg-primary text-white rounded-radius hover:bg-primary-strong transition-colors shadow-sm">
            Compute Fees
        </button>
    </aside>
</div>
