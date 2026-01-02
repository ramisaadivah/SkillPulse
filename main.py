import os
import uvicorn
import io
from fastapi import FastAPI, UploadFile, File
from pypdf import PdfReader

app = FastAPI()

# Database of skills to detect in text
SKILL_DB = ["python", "javascript", "php", "laravel", "sql", "aws", "react", "tailwind", "machine learning", "nlp", "security", "docker", "git", "java", "linux"]

def extract_text_from_pdf(pdf_content):
    """Extracts text from a raw PDF byte stream."""
    reader = PdfReader(io.BytesIO(pdf_content))
    return " ".join([page.extract_text() for page in reader.pages]).lower()

@app.post("/compare-job-resume")
async def compare_job_resume(resume: UploadFile = File(...), jd: UploadFile = File(...)):
    # 1. Extract text from both uploaded PDF files
    resume_text = extract_text_from_pdf(await resume.read())
    jd_text = extract_text_from_pdf(await jd.read())

    # 2. Detect skills using intersection with SKILL_DB
    resume_skills = {s for s in SKILL_DB if s in resume_text}
    jd_skills = {s for s in SKILL_DB if s in jd_text}

    # 3. Calculate match metrics
    matched = list(resume_skills.intersection(jd_skills))
    missing = list(jd_skills - resume_skills)
    
    # Calculate score as a percentage of JD requirements met
    score = (len(matched) / len(jd_skills)) * 100 if jd_skills else 0

    # 4. Map detected skills to career paths
    career_map = {
        "python": "Data Scientist",
        "laravel": "Full Stack Developer",
        "security": "Cybersecurity Analyst",
        "react": "Frontend Engineer"
    }
    other_options = [career_map[s] for s in resume_skills if s in career_map]

    return {
        "score": round(score),
        "matched": matched,
        "missing": missing,
        "other_careers": list(set(other_options))
    }

# CRITICAL: This must be at the bottom so all functions are loaded first
if __name__ == "__main__":
    # Render assigns a port dynamically via the PORT environment variable
    # We default to 10000 if the variable isn't set
    port = int(os.environ.get("PORT", 10000))
    uvicorn.run(app, host="0.0.0.0", port=port)