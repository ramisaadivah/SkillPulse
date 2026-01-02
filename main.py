# main.py
from fastapi import FastAPI, UploadFile, File, HTTPException
from pypdf import PdfReader
import io

app = FastAPI()

SKILLS = [
    "python", "javascript", "php", "laravel", "sql", "aws",
    "react", "tailwind", "machine learning", "nlp",
    "security", "docker", "git", "java", "linux"
]

def extract_text(pdf_bytes: bytes) -> str:
    try:
        reader = PdfReader(io.BytesIO(pdf_bytes))
        return " ".join(page.extract_text() or "" for page in reader.pages).lower()
    except Exception:
        raise HTTPException(status_code=400, detail="Invalid PDF file")

@app.post("/compare-job-resume")
async def compare(resume: UploadFile = File(...), jd: UploadFile = File(...)):
    if resume.content_type != "application/pdf" or jd.content_type != "application/pdf":
        raise HTTPException(status_code=415, detail="PDF files only")

    resume_text = extract_text(await resume.read())
    jd_text = extract_text(await jd.read())

    resume_skills = {s for s in SKILLS if s in resume_text}
    jd_skills = {s for s in SKILLS if s in jd_text}

    matched = sorted(resume_skills & jd_skills)
    missing = sorted(jd_skills - resume_skills)

    score = round((len(matched) / len(jd_skills)) * 100, 2) if jd_skills else 0

    return {
        "score": score,
        "matched": matched,
        "missing": missing
    }

@app.get("/")
def health():
    return {"status": "ok"}
