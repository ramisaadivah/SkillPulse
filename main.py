from fastapi import FastAPI, UploadFile, File
from pypdf import PdfReader
import io

app = FastAPI()

if __name__ == "__main__":
    # Get the port from Render's environment, default to 10000 if not found
    port = int(os.environ.get("PORT", 10000))
    uvicorn.run(app, host="0.0.0.0", port=port)

SKILL_DB = ["python", "javascript", "php", "laravel", "sql", "aws", "react", "tailwind", "machine learning", "nlp", "security", "docker", "git", "java", "linux"]

def extract_text_from_pdf(pdf_content):
    reader = PdfReader(io.BytesIO(pdf_content))
    return " ".join([page.extract_text() for page in reader.pages]).lower()

@app.post("/compare-job-resume")
async def compare_job_resume(resume: UploadFile = File(...), jd: UploadFile = File(...)):
    # Extract text from both
    resume_text = extract_text_from_pdf(await resume.read())
    jd_text = extract_text_from_pdf(await jd.read())

    # Detect skills in both
    resume_skills = {s for s in SKILL_DB if s in resume_text}
    jd_skills = {s for s in SKILL_DB if s in jd_text}

    # Calculations
    matched = list(resume_skills.intersection(jd_skills))
    missing = list(jd_skills - resume_skills)
    
    # Calculate score based on JD requirements
    score = (len(matched) / len(jd_skills)) * 100 if jd_skills else 0

    # Career Suggestions based on Resume Skills
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