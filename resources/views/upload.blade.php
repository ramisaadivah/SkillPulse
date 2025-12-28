<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'aesthetic': {
                            'base': '#FFFFFF',
                            'lavender': '#C4B5FD',
                            'rose': '#FDA4AF',
                            'mint': '#99F6E4',
                            'sky': '#7DD3FC',
                            'ink': '#0F172A',
                            'dusty': '#334155',
                        },
                    },
                    boxShadow: {
                        'bright': '0 10px 30px -5px rgba(0, 0, 0, 0.15), 0 8px 15px -6px rgba(0, 0, 0, 0.1)',
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>SkillPulse - Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            letter-spacing: -0.025em;
        }
        .high-contrast-card {
            background: #FFFFFF;
            border: 2px solid #F1F5F9;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen text-aesthetic-ink">

    <div class="bg-aesthetic-ink text-white px-6 py-2 flex justify-between items-center text-[10px] font-bold tracking-widest uppercase">
        <span><span class="text-aesthetic-mint">●</span> Database: {{ Auth::user()->detected_skills === null ? 'Empty' : 'Active (' . count(Auth::user()->detected_skills) . ')' }}</span>
        <span>User: {{ Auth::user()->email }}</span>
    </div>

    <nav class="p-6">
        <div class="max-w-6xl mx-auto flex justify-between items-center bg-white rounded-3xl px-8 py-5 border-2 border-slate-100 shadow-bright">
            <h1 class="text-2xl font-extrabold text-aesthetic-ink flex items-center tracking-tighter">
                <span class="bg-aesthetic-ink text-white w-10 h-10 flex items-center justify-center rounded-xl mr-3 shadow-lg">SP</span> 
                SkillPulse AI
            </h1>
            <div class="flex items-center space-x-8">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-extrabold text-aesthetic-ink">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-blue-600 font-black uppercase tracking-widest">Premium Dashboard</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-slate-900 hover:bg-red-600 text-white px-6 py-2.5 rounded-2xl text-xs font-black transition-all shadow-md">
                        LOGOUT
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 pb-12">
        <div class="grid lg:grid-cols-3 gap-10">
            
            <div class="lg:col-span-1 space-y-8">
                
                <div class="high-contrast-card p-8 rounded-[2rem] shadow-bright">
                    <h2 class="text-xl font-extrabold text-aesthetic-ink mb-6 flex items-center">
                        <span class="w-2 h-7 bg-aesthetic-rose rounded-full mr-3"></span> Profile Overview
                    </h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Degree / Qualification</label>
                            <p class="text-aesthetic-ink font-bold text-lg leading-tight">{{ Auth::user()->education_level ?? 'Not Provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-3 block">Expertise Tags</label>
                            <div class="flex flex-wrap gap-2">
                                @forelse(Auth::user()->detected_skills ?? [] as $skill)
                                    <span class="bg-aesthetic-sky/20 text-blue-800 text-xs font-extrabold px-4 py-1.5 rounded-full border-2 border-aesthetic-sky/30 uppercase">
                                        {{ $skill }}
                                    </span>
                                @empty
                                    <p class="text-slate-400 text-xs italic">Add skills to begin matching.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="pt-6 border-t-2 border-slate-50">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Active Document</label>
                            @if(Auth::user()->resume_path)
                                <div class="flex items-center justify-between mt-3 bg-slate-50 p-4 rounded-2xl border-2 border-slate-100">
                                    <div class="flex items-center">
                                        <div class="bg-aesthetic-ink text-white p-2 rounded-xl">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span class="ml-4 text-sm font-black text-aesthetic-ink">CV_VERIFIED.PDF</span>
                                    </div>
                                    <span class="text-[9px] bg-green-500 text-white px-3 py-1 rounded-full font-black uppercase shadow-sm">LIVE</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <a href="{{ route('profile.edit') }}" class="mt-10 block w-full py-4 text-center bg-aesthetic-sky hover:bg-aesthetic-ink hover:text-white text-aesthetic-ink rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all border-b-4 border-black/10 active:border-b-0 shadow-lg">
                        Edit Profile Data
                    </a>
                </div>

                
            </div>

            <div class="lg:col-span-2 space-y-10">
                
                <div class="bg-white p-10 md:p-14 rounded-[3rem] shadow-bright border-2 border-slate-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-aesthetic-lavender/30 rounded-full -mr-24 -mt-24 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-aesthetic-rose/20 rounded-full -ml-24 -mb-24 blur-3xl"></div>

                    <div class="mb-12 relative z-10">
                        <h2 class="text-5xl font-black text-aesthetic-ink tracking-tighter leading-none mb-4">Resume AI</h2>
                        <p class="text-lg text-slate-600 font-medium">Deep-match analysis for best career options!</p>
                    </div>

                    <form action="{{ route('analyze') }}" method="POST" enctype="multipart/form-data" class="space-y-12 relative z-10">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-10">
                            <div class="space-y-4">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">01. Target Job Spec</label>
                                <div class="relative group h-48">
                                    <input type="file" name="jd_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                    <div class="h-full bg-slate-50 border-4 border-dashed border-slate-200 rounded-[2.5rem] flex flex-col items-center justify-center group-hover:bg-aesthetic-sky/10 group-hover:border-aesthetic-sky transition-all duration-300">
                                        <div class="bg-aesthetic-ink text-white p-4 rounded-2xl mb-4 shadow-xl">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        </div>
                                        <span class="text-xs font-black text-aesthetic-ink uppercase">Upload Job Spec</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">02. Analysis Source</label>
                                <div class="space-y-4">
                                    <div class="bg-white border-2 border-slate-100 p-6 rounded-3xl shadow-sm hover:border-aesthetic-rose transition-all group">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="resume_source" value="saved" checked onclick="toggleNewUpload(false)" class="w-5 h-5 text-aesthetic-ink focus:ring-black border-slate-300">
                                            <span class="ml-4 text-sm font-extrabold text-aesthetic-ink uppercase">Stored Document</span>
                                        </label>
                                    </div>
                                    

                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-aesthetic-ink text-white font-black py-6 rounded-[2rem] shadow-2xl hover:scale-[1.02] active:scale-[0.98] transition-all uppercase tracking-[0.3em] text-sm">
                            Execute Intelligence Match
                        </button>
                    </form>
                </div>

                <div class="bg-aesthetic-ink p-12 rounded-[3.5rem] shadow-2xl relative overflow-hidden">
                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <h3 class="text-3xl font-black text-white tracking-tighter">Career Matchmaker</h3>
                            <p class="text-aesthetic-rose font-bold uppercase tracking-widest text-xs mt-2">Roles Identified for Your Profile</p>
                        </div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-8">
                        @forelse($jobs as $job)
                            <div class="bg-white p-8 rounded-[2.5rem] shadow-lg group hover:bg-aesthetic-mint transition-all duration-300">
                                <h4 class="font-black text-xl text-aesthetic-ink mb-6 leading-tight">{{ $job->title }}</h4>
                                <div class="flex items-center justify-between border-t-2 border-slate-50 pt-5">
                                    
                                    <span class="text-[10px] font-black text-slate-400 uppercase group-hover:text-aesthetic-ink">View Role →</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-20 text-center border-4 border-dashed border-white/10 rounded-[3rem]">
                                <p class="text-white/30 font-black uppercase tracking-[0.4em] text-xs">Awaiting Analysis Results</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Toggle function for the New Upload section
        function toggleNewUpload(show) {
            const field = document.getElementById('new-resume-field');
            const fileInput = field.querySelector('input[type="file"]');
            if (show) {
                field.classList.remove('hidden');
                fileInput.setAttribute('required', 'required');
            } else {
                field.classList.add('hidden');
                fileInput.removeAttribute('required');
            }
        }

        const ctx = document.getElementById('progressChart')?.getContext('2d');
        if(ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($history->pluck('created_at')->map(fn($d) => date('M d', strtotime($d)))) !!},
                    datasets: [{
                        label: 'Match Score',
                        data: {!! json_encode($history->pluck('score')) !!},
                        borderColor: '#0F172A',
                        backgroundColor: 'rgba(125, 211, 252, 0.4)',
                        borderWidth: 6,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0F172A',
                        pointRadius: 6,
                        pointHoverRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            display: true, 
                            beginAtZero: true, 
                            max: 100,
                            ticks: { font: { weight: '800', size: 10 }, color: '#0F172A' },
                            grid: { color: '#F1F5F9' }
                        },
                        x: { 
                            ticks: { font: { weight: '800', size: 10 }, color: '#0F172A' },
                            grid: { display: false } 
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>