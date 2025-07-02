{{-- resources/views/components/dashboard/welcome-banner.blade.php --}}
@props(['userRoleLabel', 'userName'])

<div
    class="backdrop-blur-xl bg-gray-50/80 border border-gray-200/50 p-8 rounded-2xl shadow-lg mb-8 transform hover:scale-[1.02] transition-all duration-500 group animate-fade-in-up dark:bg-purple-900/10 dark:border-purple-800/20 dark:shadow-2xl">
    <div
        class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-purple-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 dark:from-blue-600/10 dark:to-purple-600/10">
    </div>
    <div class="relative z-10">
        <h2
            class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent animate-gradient-x dark:from-blue-400 dark:via-purple-400 dark:to-indigo-400">
            Welcome <span class="capitalize">{{ $userRoleLabel ?? 'Pengguna' }}</span>,
            {{ $userName ?? 'User' }}!
        </h2>
        <p class="text-blue-700/80 mt-3 text-lg animate-fade-in delay-300 dark:text-blue-300/80">Selamat datang
            di dashboard Anda. Mari kelola inventaris Anda dengan mudah.</p>
    </div>
</div>