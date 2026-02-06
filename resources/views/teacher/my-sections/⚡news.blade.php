<?php

use Livewire\Component;

new class extends Component {
  //
};
?>

<div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm">
  <div class="flex items-center space-x-4 mb-6">
    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
        class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.357.205a.75.75 0 0 1-1.1-.512l-.814-2.406m5.13-9.18c.31.1.615.22.91.36m-3.14 8.82c.31-.1.615-.22.91-.36M18.342 6.345a9.755 9.755 0 0 1 2.378 3.965m-2.378 7.345a9.755 9.755 0 0 0 2.378-3.965m-2.378 3.965a9.755 9.755 0 0 1-2.28-4.695m2.28 4.695c-.492.308-1.018.574-1.577.798m1.577-8.838a9.755 9.755 0 0 0-2.28 4.695m0 0a9.755 9.755 0 0 1-2.28-4.695m0 0c-.492.308-1.018.574-1.577.798" />
      </svg>
    </div>
    <div>
      <h4 class="font-bold text-gray-900">Weekly Announcement</h4>
      <p class="text-xs text-gray-400 font-medium">Posted 2 hours ago</p>
    </div>
  </div>
  <p class="text-sm text-gray-600 leading-relaxed mb-6">
    Welcome to the new semester! Please ensure all group members are registered by the end of the week. We
    will be starting our first module on Advanced Physics tomorrow at 8:00 AM.
  </p>
  <div class="bg-gray-50 rounded-2xl p-4 flex items-center justify-between">
    <span class="text-xs font-bold text-gray-500">Module_1_Intro.pdf</span>
    <button class="text-blue-600 text-xs font-bold">Download</button>
  </div>
</div>
