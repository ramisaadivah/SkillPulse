<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>SkillPulse Analysis Results</title>
</head>
<body class="bg-slate-50 p-6 md:p-12">
    <div class="max-w-5xl mx-auto">
        
        <div class="bg-white rounded-3xl shadow-sm border p-8 mb-8 flex flex-col md:flex-row justify-between items-center">
            <div>
                <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-1">Career Readiness Report</h2>
                <h1 class="text-4xl font-black text-slate-800">{{ $jobTitle }}</h1>
            </div>
            <div class="text-center mt-6 md:mt-0">
                <div class="text-5xl font-black text-blue-600">{{ $score }}%</div>
                <p class="text-slate-500 font-medium">Match Score</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-green-100">
                <h3 class="text-green-600 font-bold text-lg mb-4 flex items-center">
                    <span class="bg-green-100 p-2 rounded-lg mr-3">✔</span> Skills Detected
                </h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($matched as $skill)
                        <span class="bg-green-50 text-green-700 border border-green-200 px-4 py-2 rounded-xl text-sm font-bold uppercase">{{ $skill }}</span>
                    @empty
                        <p class="text-slate-400 italic">No matching skills detected in your resume.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-red-100">
                <h3 class="text-red-600 font-bold text-lg mb-4 flex items-center">
                    <span class="bg-red-100 p-2 rounded-lg mr-3">✖</span> Gap Identified
                </h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($missing as $skill)
                        <span class="bg-red-50 text-red-700 border border-red-200 px-4 py-2 rounded-xl text-sm font-bold uppercase">{{ $skill }}</span>
                    @empty
                        <p class="text-green-600 font-bold">Your resume perfectly matches the Job Specification!</p>
                    @endforelse
                </div>
            </div>
        </div>

        @if(isset($other_careers) && count($other_careers) > 0)
        <div class="mb-8 bg-indigo-600 rounded-3xl p-8 text-white shadow-lg">
            <div class="flex items-center mb-6">
                <span class="bg-white/20 p-2 rounded-lg mr-4">💡</span>
                <h3 class="text-2xl font-bold">Alternative Career Paths</h3>
            </div>
            <p class="text-indigo-100 mb-6">Based on the skills we found in your resume, you also have a high affinity for these roles:</p>
            <div class="flex flex-wrap gap-3">
                @foreach($other_careers as $career)
                    <span class="bg-white text-indigo-600 px-5 py-2 rounded-full font-bold text-sm shadow-sm">
                        {{ $career }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 mb-8">
            <h3 class="text-slate-800 font-bold text-xl mb-4">📈 Room for Improvement</h3>
            <div class="prose text-slate-600 max-w-none">
                @if($score < 50)
                    <p>There is a significant gap between your profile and this specification. We recommend focusing on foundational projects in <b>{{ $missing[0] ?? 'the missing areas' }}</b> before applying.</p>
                @elseif($score < 80)
                    <p>You are a strong candidate! To move into the top 10% of applicants, try to incorporate the <b>{{ count($missing) }}</b> missing keywords into your resume experience section.</p>
                @else
                    <p>Excellent match. Your resume is highly optimized for this specification. Focus your preparation on technical interviews related to <b>{{ $matched[0] ?? 'your core skills' }}</b>.</p>
                @endif
            </div>
        </div>

        @if(count($roadmap) > 0)
        <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-2xl">
            <h3 class="text-2xl font-bold mb-8 flex items-center">
                <span class="bg-blue-600 p-2 rounded-lg mr-4">🚀</span> Learning Roadmap
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                @foreach($roadmap as $skill => $data)
                    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700">
                        <div class="text-blue-400 text-xs font-bold uppercase mb-1">{{ $data['topic'] }}</div>
                        <h4 class="text-xl font-bold mb-3">Mastering {{ $skill }}</h4>
                        <p class="text-slate-400 text-sm mb-4">{{ $data['project'] }}</p>
                        <div class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-lg cursor-pointer transition">
                            VIEW RESOURCES
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4">
            <button onclick="window.print()" class="bg-slate-800 text-white px-10 py-4 rounded-2xl font-bold hover:bg-slate-700 transition shadow-sm">
                Download Report (PDF)
            </button>
            <a href="/upload" class="bg-white border border-slate-300 text-slate-600 px-10 py-4 rounded-2xl font-bold hover:bg-slate-100 transition shadow-sm">
                Analyze Another
            </a>
        </div>
    </div>
</body>
</html>