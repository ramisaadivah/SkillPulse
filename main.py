import io
from fastapi import FastAPI, UploadFile, File
from pypdf import PdfReader

app = FastAPI()

# Skills database
SKILL_DB = [
    "python", "javascript", "php", "laravel", "sql", "aws", 
    "react", "tailwind", "machine learning", "nlp", 
    "security", "docker", "git", "java", "linux"
]

# Career mapping
CAREER_MAP = {
    "python": "Data Scientist",
    "laravel": "Full Stack Developer",
    "security": "Cybersecurity Analyst",
    "react": "Frontend Engineer"
}

def extract_text_from_pdf(pdf_content):
    """Extract all text from a PDF file"""
    reader = PdfReader(io.BytesIO(pdf_content))
    text = ""
    for page in reader.pages:
        page_text = page.extract_text()
        if page_text:
            text += page_text + " "
    return text.lower()

@app.get("/")
def root():
    return {"status": "ok"}

@app.post("/compare-job-resume")
async def compare_job_resume(
    resume: UploadFile = File(...), 
    jd: UploadFile = File(...)
):
    # Extract text from PDFs
    resume_text = extract_text_from_pdf(await resume.read())
    jd_text = extract_text_from_pdf(await jd.read())

    # Detect skills
    resume_skills = {s for s in SKILL_DB if s in resume_text}
    jd_skills = {s for s in SKILL_DB if s in jd_text}

    # Match and missing skills
    matched = list(resume_skills.intersection(jd_skills))
    missing = list(jd_skills - resume_skills)

    # Compute match score
    score = (len(matched) / len(jd_skills) * 100) if jd_skills else 0

    # Suggest other careers based on resume skills
    other_careers = [CAREER_MAP[s] for s in resume_skills if s in CAREER_MAP]

    return {
        "score": round(score),
        "matched": matched,
        "missing": missing,
        "other_careers": list(set(other_careers))
    }
