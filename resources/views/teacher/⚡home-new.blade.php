<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::new-layout')]
  class extends Component {
};
?>
<x-slot name="title">Home</x-slot>
<div class="max-w-7xl mx-auto">
  <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Home <span class="text-gray-400 font-normal"></span></h1>
    </div>
    <div class="hidden md:flex space-x-6 mt-4 md:mt-0 text-sm font-medium text-gray-500 border-b md:border-none">
      <a href="#" class="text-blue-600 border-b-2 border-blue-600 pb-1">Overview</a>
      <a href="#" class="hover:text-gray-800">Recently accessed</a>
      <a href="#" class="hover:text-gray-800">Competency</a>
    </div>
  </div>

  <div class="hidden md:grid md:grid-cols-3 gap-6 mb-10">
    <div class="bg-blue-600 rounded-2xl p-6 text-white relative overflow-hidden">
      <p class="text-sm opacity-80">Progress</p>
      <h3 class="text-3xl font-bold mt-2">1735</h3>
      <div class="mt-4 flex items-end space-x-1 h-12">
        <div class="w-2 bg-blue-400 h-1/2"></div>
        <div class="w-2 bg-blue-400 h-3/4"></div>
        <div class="w-2 bg-white h-full"></div>
        <div class="w-2 bg-blue-400 h-2/3"></div>
      </div>
    </div>
    <div class="bg-purple-800 rounded-2xl p-6 text-white">
      <p class="text-sm opacity-80">Badges in progress</p>
      <div class="flex items-center mt-4 space-x-2">
        <div class="w-10 h-10 rounded-full bg-white/20"></div>
        <div class="w-10 h-10 rounded-full bg-white/20"></div>
        <div class="text-xl font-bold ml-auto">8 <span class="text-xs block font-normal opacity-60">more</span>
        </div>
      </div>
    </div>
    <div class="bg-gray-800 rounded-2xl p-6 text-white">
      <p class="text-sm opacity-80">Badges completed</p>
      <div class="flex items-center mt-4 space-x-2">
        <div class="w-10 h-10 rounded-full bg-white/20"></div>
        <div class="w-10 h-10 rounded-full bg-white/20"></div>
        <div class="text-xl font-bold ml-auto">12 <span class="text-xs block font-normal opacity-60">more</span>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">News</h2>
        {{-- <button class="text-sm text-gray-500 border rounded-lg px-3 py-1 flex items-center">
          Recent <i class="fa-solid fa-chevron-down ml-2 text-[10px]"></i>
        </button> --}}
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach (range(1, 4) as $item)
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start mb-4">
              <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Developer</span>
              <div class="w-10 h-10 bg-blue-100 rounded-lg"></div>
            </div>
            <h4 class="font-bold text-gray-800 mb-2 leading-tight">Microsoft Certified: Developer Expert
            </h4>
            <p class="text-xs text-gray-500 mb-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit...
            </p>
            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
              <i class="fa-regular fa-bookmark text-gray-400"></i>
              <a href="#" class="text-blue-600 text-xs font-bold flex items-center">
                See Dashboard <i class="fa-solid fa-circle-arrow-right ml-2"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="lg:col-span-1">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Timeline</h2>
        <button class="text-sm text-gray-500 border rounded-lg px-3 py-1 flex items-center">
          By time <i class="fa-solid fa-chevron-down ml-2 text-[10px]"></i>
        </button>
      </div>

      <div class="space-y-6">
        @foreach (range(1, 3) as $time)
          <div class="relative pl-4">
            <p class="text-[10px] font-bold text-gray-400 uppercase">Friday, 20 March, 16:30</p>
            <h5 class="text-sm font-bold text-gray-800 mt-1">Moodle Course Activity Features</h5>
            <p class="text-xs text-gray-400">Microsoft Teacher</p>
            <button
              class="mt-3 w-full py-2 border border-blue-100 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-50">
              Add Submission
            </button>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>