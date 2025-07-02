{{-- resources/views/partials/dashboard-activity-log.blade.php --}}

<div id="activity-section"
    class="backdrop-blur-xl bg-gray-50/80 border border-gray-200/50 p-8 rounded-2xl shadow-lg animate-fade-in-up delay-500 dark:bg-white/5 dark:border-white/10 dark:shadow-2xl">
    <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center dark:text-gray-100">
        <span class="text-2xl mr-3">🔥</span>Aktivitas Hari Ini
        <div class="ml-auto w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
    </h3>
    @if (isset($recentActivities) && $recentActivities->isNotEmpty())
        <div
            class="backdrop-blur-sm bg-white/80 border border-gray-200/50 rounded-xl overflow-hidden dark:bg-white/5 dark:border-white/10">
            <div
                class="bg-gradient-to-r from-gray-100 to-gray-50 text-gray-800 font-medium px-6 py-4 flex text-sm border-b border-gray-200 dark:from-gray-800/50 dark:to-gray-700/50 dark:text-gray-200 dark:border-white/10">
                <span class="w-2/5 flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>Aksi
                </span>
                <span class="w-1/4 text-center text-gray-700 dark:text-gray-100">Waktu</span>
                <span class="w-1/3 text-right pr-3 text-gray-700 dark:text-gray-100">Pengguna</span>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-white/10">
                @foreach ($recentActivities as $index => $activity)
                    <div class="px-6 py-4 flex items-center text-sm hover:bg-gray-100/50 transition-all duration-300 animate-fade-in-up dark:hover:bg-gray-700/15"
                        style="animation-delay: {{ $index * 50 }}ms">
                        <div class="w-2/5 truncate">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 border border-blue-200 text-blue-700 text-xs font-semibold hover:scale-105 transition-transform duration-300 dark:from-blue-500/20 dark:to-indigo-500/20 dark:border-blue-400/30 dark:text-blue-300">
                                {{ $activity->action ?? 'Tidak ada aksi' }}
                            </span>
                        </div>
                        <div class="w-1/4 text-center text-gray-600 truncate dark:text-gray-300">
                            {{ $activity->created_at->diffForHumans() }}
                        </div>
                        <div class="w-1/3 flex justify-end">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-gray-100 to-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold hover:scale-105 transition-transform duration-300 dark:from-gray-600/50 dark:to-gray-500/50 dark:border-gray-400/30 dark:text-gray-200">
                                {{ $activity->user->name ?? 'Unknown User' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @if (method_exists($recentActivities, 'links'))
            <div class="mt-8 flex justify-center">
                {{ $recentActivities->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4 opacity-50">📭</div>
            <p class="text-gray-600 text-lg dark:text-gray-300">Tidak ada aktivitas untuk hari ini.</p>
        </div>
    @endif
</div>