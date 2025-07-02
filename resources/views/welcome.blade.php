<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stockify - Smart Inventory Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e40af',
                        accent: '#f59e0b',
                        dark: '#0f172a',
                        'dark-light': '#1e293b'
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'fade-in-up': 'slideUp 0.8s ease-out forwards', /* New animation */
                        'bounce-slow': 'bounce 2s infinite', /* New animation */
                    },
                    keyframes: { /* Add keyframes for new animations */
                        slideUp: {
                            '0%': {
                                transform: 'translateY(30px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        bounce: {
                            '0%, 100%': {
                                transform: 'translateY(-10%)',
                                'animation-timing-function': 'cubic-bezier(0.8, 0, 1, 1)'
                            },
                            '50%': {
                                transform: 'none',
                                'animation-timing-function': 'cubic-bezier(0, 0, 0.2, 1)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {
            0%,
            100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* Existing fadeIn and slideUp are now in tailwind.config keyframes */

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-gradient {
            background: radial-gradient(ellipse at center, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
        }

        /* New: Hide elements initially for scroll animation */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .animate-on-scroll.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Particle effect styles */
        .particles-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: -1; /* Ensures particles are behind content */
        }

        .particle {
            position: absolute;
            background-color: rgba(59, 130, 246, 0.6); /* Primary color with transparency */
            border-radius: 50%;
            animation: moveParticles linear infinite;
        }

        @keyframes moveParticles {
            0% {
                transform: translate(0, 0);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translate(var(--x-end), var(--y-end));
                opacity: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <button x-data="{ showButton: false }" x-init="window.onscroll = () => { showButton = (window.pageYOffset > 300) }" x-show="showButton" x-transition.opacity.duration.300ms @click="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-6 right-6 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-colors focus:outline-none z-50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <nav class="fixed w-full top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center animate-bounce-slow">
                            <span class="text-white font-bold text-xl">S</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <span class="text-2xl font-bold text-gray-900">Stockify</span>
                    </div>
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#features" class="text-gray-700 hover:text-blue-600 transition-colors">Features</a>
                        <a href="#how-it-works" class="text-gray-700 hover:text-blue-600 transition-colors">How it Works</a>
                        <a href="#roles" class="text-gray-700 hover:text-blue-600 transition-colors">Roles</a>
                        <a href="#pricing" class="text-gray-700 hover:text-blue-600 transition-colors">Pricing</a>
                        <a href="#faq" class="text-gray-700 hover:text-blue-600 transition-colors">FAQ</a>
                        <a href="#contact" class="text-gray-700 hover:text-blue-600 transition-colors">Contact</a>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                     <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition-colors hidden md:block">Sign In</a>
    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-colors hidden md:block">
        Get Started
    </a>
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 md:hidden">
                        <span class="sr-only">Open main menu</span>
                        <svg x-show="!open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="open" x-transition:enter="duration-200 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="duration-100 ease-in" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#features" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Features</a>
                <a href="#how-it-works" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">How it Works</a>
                <a href="#roles" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Roles</a>
                <a href="#technology" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Technology</a>
                <a href="#pricing" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Pricing</a>
                <a href="#faq" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">FAQ</a>
                <a href="#contact" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Contact</a>
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 border-t border-gray-100 pt-3 mt-3">Sign In</a>
<a href="{{ route('register') }}" class="block w-full bg-blue-600 text-white px-3 py-2 rounded-md text-base font-medium text-center hover:bg-blue-700 mt-2">Get Started</a>
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="hero-gradient absolute inset-0"></div>
        <div class="particles-container" id="particles-js"></div> 

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6 animate-slide-up">
                    Smart Inventory
                    <span class="text-gradient block">Management</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto animate-fade-in">
                    Streamline your warehouse operations with intelligent stock management, real-time tracking, and
                    automated reporting.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16 animate-fade-in">
                    <button class="bg-blue-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-blue-700 transition-all transform hover:scale-105 shadow-lg">
                        Start Free Trial
                    </button>
                    <button class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-full text-lg font-semibold hover:border-blue-600 hover:text-blue-600 transition-all">
                        Watch Demo
                    </button>
                </div>
            </div>

            <div class="relative max-w-5xl mx-auto animate-on-scroll">
                <div class="bg-white rounded-2xl shadow-2xl p-6 animate-float">
                    <div class="bg-gray-100 rounded-xl p-8 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <div class="text-sm text-gray-500">Dashboard</div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <div class="text-2xl font-bold text-blue-600">1,234</div>
                                <div class="text-sm text-gray-600">Total Products</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <div class="text-2xl font-bold text-green-600">$45,678</div>
                                <div class="text-sm text-gray-600">Inventory Value</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <div class="text-2xl font-bold text-purple-600">89%</div>
                                <div class="text-sm text-gray-600">Stock Efficiency</div>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="h-24 bg-gradient-to-r from-blue-500 to-purple-600 rounded opacity-20"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Powerful Features for
                    <span class="text-gradient">Modern Businesses</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Everything you need to manage your inventory efficiently and scale your business
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-8 rounded-2xl bg-gray-50 card-hover animate-on-scroll">
                    <div class="w-16 h-16 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Inventory Tracking</h3>
                    <p class="text-gray-600">Real-time monitoring of stock levels with automated alerts for low
                        inventory and reorder points.</p>
                </div>

                <div class="text-center p-8 rounded-2xl bg-gray-50 card-hover animate-on-scroll">
                    <div class="w-16 h-16 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Analytics & Reports</h3>
                    <p class="text-gray-600">Comprehensive reporting with insights into stock movement, sales trends,
                        and inventory performance.</p>
                </div>

                <div class="text-center p-8 rounded-2xl bg-gray-50 card-hover animate-on-scroll">
                    <div class="w-16 h-16 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Team Management</h3>
                    <p class="text-gray-600">Role-based access control for admins, warehouse managers, and staff with
                        customizable permissions.</p>
                </div>

                <div class="text-center p-8 rounded-2xl bg-gray-50 card-hover animate-on-scroll">
                    <div class="w-16 h-16 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Automation</h3>
                    <p class="text-gray-600">Automated workflows for stock replenishment, order processing, and
                        inventory adjustments.</p>
                </div>

                <div class="text-center p-8 rounded-2xl bg-gray-50 card-hover animate-on-scroll">
                    <div class="w-16 h-16 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Security</h3>
                    <p class="text-gray-600">Enterprise-grade security with data encryption, backup solutions, and
                        compliance management.</p>
                </div>

                <div class="text-center p-8 rounded-2xl bg-gray-50 card-hover animate-on-scroll">
                    <div class="w-16 h-16 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Integrations</h3>
                    <p class="text-gray-600">Seamless integration with popular POS systems, e-commerce platforms, and
                        accounting software.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Simple, Transparent
                    <span class="text-gradient">Pricing</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Choose the perfect plan for your business needs
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover animate-on-scroll">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Basic</h3>
                        <div class="text-4xl font-bold text-blue-600 mb-2">$10</div>
                        <div class="text-gray-600 mb-6">per month</div>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Up to 100 products
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Basic reporting
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Email support
                            </li>
                        </ul>
                        <button class="w-full bg-gray-100 text-gray-900 py-3 rounded-full font-semibold hover:bg-gray-200 transition-colors">
                            Choose Basic
                        </button>
                    </div>
                </div>

                <div class="bg-blue-600 text-white p-8 rounded-2xl shadow-lg card-hover relative animate-on-scroll">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-accent text-white px-4 py-1 rounded-full text-sm font-semibold">Most
                            Popular</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-bold mb-4">Pro</h3>
                        <div class="text-4xl font-bold mb-2">$20</div>
                        <div class="text-blue-200 mb-6">per month</div>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Up to 1,000 products
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Advanced analytics
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Team collaboration
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Priority support
                            </li>
                        </ul>
                        <button class="w-full bg-white text-blue-600 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                            Choose Pro
                        </button>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover animate-on-scroll">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Enterprise</h3>
                        <div class="text-4xl font-bold text-blue-600 mb-2">$30</div>
                        <div class="text-gray-600 mb-6">per month</div>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Unlimited products
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Custom integrations
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Advanced security
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                24/7 phone support
                            </li>
                        </ul>
                        <button class="w-full bg-gray-100 text-gray-900 py-3 rounded-full font-semibold hover:bg-gray-200 transition-colors">
                            Choose Enterprise
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        Built for
                        <span class="text-gradient">Modern Warehouses</span>
                    </h2>
                    <p class="text-xl text-gray-600 mb-8">
                        We understand the challenges of inventory management. That's why we built Stockify with
                        cutting-edge technology and user-friendly design to help businesses of all sizes streamline
                        their operations.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-gray-700">Real-time inventory tracking</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-gray-700">Automated reporting and analytics</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-gray-700">Seamless team collaboration</span>
                        </div>
                    </div>
                </div>
                <div class="relative animate-on-scroll">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-8 text-white">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-2">10k+</div>
                                <div class="text-sm opacity-90">Active Users</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-2">99.9%</div>
                                <div class="text-sm opacity-90">Uptime</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-2">50M+</div>
                                <div class="text-sm opacity-90">Items Tracked</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-2">24/7</div>
                                <div class="text-sm opacity-90">Support</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600 animate-on-scroll">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Transform Your Inventory?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Join thousands of businesses that trust Stockify for their inventory management needs.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-white text-blue-600 px-8 py-4 rounded-full text-lg font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                    Get Started Now
                </button>
                <button class="border-2 border-blue-200 text-white px-8 py-4 rounded-full text-lg font-semibold hover:border-white hover:text-white transition-all">
                    Contact Sales
                </button>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    How Stockify
                    <span class="text-gradient">Streamlines Your Workflow</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    A comprehensive overview of Stockify's core functionalities
                </p>
            </div>

            <div class="space-y-16">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center animate-on-scroll">
                    <div class="order-2 md:order-1">
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Product Management
                        </h3>
                        <p class="text-lg text-gray-600 mb-4">
                            Simplify product data entry and organization. Admins can define
                            product categories and attributes, while Warehouse Managers add new products with detailed
                            information including images. All data is securely stored and easily accessible.
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Add new product categories (e.g., Electronics, Apparel,
                                Food).</li>
                            <li>Define product attributes like size, color, and weight.</li>
                            <li>Record product details: name, description, purchase price,
                                selling price, initial stock, and images.</li>
                        </ul>
                    </div>
                    <div class="order-1 md:order-2">
                        <img src="https://via.placeholder.com/600x400/add8e6/808080?text=Product+Management" alt="Product Management" class="rounded-lg shadow-xl">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center animate-on-scroll">
                    <div>
                        <img src="https://via.placeholder.com/600x400/90ee90/808080?text=Stock+Management" alt="Stock Management" class="rounded-lg shadow-xl">
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Stock Management</h3>
                        <p class="text-lg text-gray-600 mb-4">
                            Keep track of every item with real-time updates. Staff Gudang
                            handles incoming and outgoing transactions, while Manajer Gudang monitors stock levels,
                            minimum stock alerts, and conducts stock opname for accuracy.
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Record inbound and outbound transactions.</li>
                            <li>Monitor real-time stock levels, including minimum stock and
                                available stock.</li>
                            <li>Perform regular stock opname for inventory reconciliation.
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center animate-on-scroll">
                    <div class="order-2 md:order-1">
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Supplier Management
                        </h3>
                        <p class="text-lg text-gray-600 mb-4">
                            Maintain a comprehensive database of your suppliers. Admins can
                            manage supplier data, while Warehouse Managers easily select suppliers when recording
                            inbound goods.
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Add, update, and delete supplier information (name, address,
                                contact).</li>
                            <li>Associate products with their respective suppliers for
                                better tracking.</li>
                        </ul>
                    </div>
                    <div class="order-1 md:order-2">
                        <img src="https://via.placeholder.com/600x400/ffb6c1/808080?text=Supplier+Management" alt="Supplier Management" class="rounded-lg shadow-xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="roles" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Defined Roles for
                    <span class="text-gradient">Seamless Collaboration</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Stockify provides distinct roles to manage access and responsibilities
                    efficiently.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover animate-on-scroll">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Admin</h3>
                    <p class="text-gray-600 mb-4">
                        The super user with full control over the application.
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Manages all aspects: CRUD for categories, suppliers, and users.</li>
                        <li>Views all reports: stock, transactions, and user activity.</li>
                        <li>Configures user access rights and general application settings.</li>
                    </ul>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover animate-on-scroll">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Warehouse Manager
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Oversees all stock and product operations within the warehouse.
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Manages stock flow: receiving and issuing goods.</li>
                        <li>Monitors stock levels and performs stock opname.</li>
                        <li>Generates stock and transaction reports.</li>
                    </ul>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover animate-on-scroll">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Warehouse Staff
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Assists the Warehouse Manager in daily operational tasks.
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Confirms receipt of incoming goods.</li>
                        <li>Prepares and dispatches outgoing goods.</li>
                        <li>Assists with stock opname processes.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Frequently Asked
                    <span class="text-gradient">Questions</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Find quick answers to the most common questions about Stockify.
                </p>
            </div>

            <div class="space-y-4">
                <div x-data="{ open: false }" class="border border-gray-200 rounded-lg shadow-sm animate-on-scroll">
                    <button @click="open = !open" class="flex justify-between items-center w-full p-6 text-left font-semibold text-lg text-gray-800 focus:outline-none">
                        <span>What is Stockify?</span>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="w-5 h-5 text-gray-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4" class="px-6 pb-6 text-gray-600">
                        Stockify is a web-based application designed to help businesses, especially those with warehouses, manage their inventory efficiently and accurately. It simplifies product tracking, streamlines transactions, and provides insightful reports.
                    </div>
                </div>

                <div x-data="{ open: false }" class="border border-gray-200 rounded-lg shadow-sm animate-on-scroll">
                    <button @click="open = !open" class="flex justify-between items-center w-full p-6 text-left font-semibold text-lg text-gray-800 focus:outline-none">
                        <span>What are the key features of Stockify?</span>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="w-5 h-5 text-gray-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4" class="px-6 pb-6 text-gray-600">
                        Stockify offers core features like Product Management (CRUD products, categories, suppliers, attributes), Stock Management (in/out transactions, monitoring, stock opname), User Management (role-based access control for Admin, Warehouse Manager, Staff), and comprehensive Reporting.
                    </div>
                </div>

                <div x-data="{ open: false }" class="border border-gray-200 rounded-lg shadow-sm animate-on-scroll">
                    <button @click="open = !open" class="flex justify-between items-center w-full p-6 text-left font-semibold text-lg text-gray-800 focus:outline-none">
                        <span>What technologies are used to build Stockify?</span>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="w-5 h-5 text-gray-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4" class="px-6 pb-6 text-gray-600">
                        Stockify is built using Laravel 10 (PHP Framework), MySQL (Database), Tailwind CSS (Frontend Framework), Flowbite (UI Component Library), and Flowbite Admin Dashboard (Template). It follows a Controller-Service-Repository architectural pattern.
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="contact" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Get in
                    <span class="text-gradient">Touch</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Have questions? We're here to help. Reach out to us.
                </p>
            </div>

            <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg animate-on-scroll">
                <form class="space-y-6">
                    <div>
                        <label for="name" class="block text-lg font-medium text-gray-700">Name</label>
                        <input type="text" id="name" name="name" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg">
                    </div>
                    <div>
                        <label for="email" class="block text-lg font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg">
                    </div>
                    <div>
                        <label for="message" class="block text-lg font-medium text-gray-700">Message</label>

                        <textarea id="message" name="message" rows="5" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg"></textarea>

                    </div>
                    <div class="text-center">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-blue-700 transition-all transform hover:scale-105 shadow-lg">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">S</span>
                </div>
                <div class="ml-3">
                    <span class="text-2xl font-bold">Stockify</span>
                </div>
            </div>
            <p class="text-gray-400 mb-6">
                &copy; 2025 Stockify. All rights reserved.
            </p>
            <div class="flex justify-center space-x-6 text-gray-400">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-white transition-colors">Sitemap</a>
            </div>
        </div>
    </footer>

    <script>
        // Intersection Observer for scroll-in animations
        document.addEventListener('DOMContentLoaded', () => {
            const animateElements = document.querySelectorAll('.animate-on-scroll');

            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            animateElements.forEach(element => {
                observer.observe(element);
            });
        });

        // Basic Particle Effect (Pure JS)
        // This is a simplified version. For more complex effects, consider libraries like particles.js
        document.addEventListener('DOMContentLoaded', () => {
            const particlesContainer = document.getElementById('particles-js');
            if (!particlesContainer) return;

            const numParticles = 30;
            const colors = ['rgba(59, 130, 246, 0.6)', 'rgba(118, 75, 162, 0.6)', 'rgba(245, 158, 11, 0.6)']; // Primary, secondary, accent with opacity

            for (let i = 0; i < numParticles; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                const size = Math.random() * 8 + 4; // Size between 4px and 12px
                const x = Math.random() * 100; // % position
                const y = Math.random() * 100; // % position
                const duration = Math.random() * 10 + 5; // Animation duration between 5s and 15s
                const delay = Math.random() * 5; // Animation delay up to 5s
                const endX = (Math.random() - 0.5) * 200; // -100% to 100% horizontal movement
                const endY = (Math.random() - 0.5) * 200; // -100% to 100% vertical movement
                const color = colors[Math.floor(Math.random() * colors.length)];

                particle.style.cssText = `
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}%;
                    top: ${y}%;
                    animation-duration: ${duration}s;
                    animation-delay: ${delay}s;
                    background-color: ${color};
                    --x-end: ${endX}px;
                    --y-end: ${endY}px;
                `;
                particlesContainer.appendChild(particle);
            }
        });
    </script>
</body>

</html>