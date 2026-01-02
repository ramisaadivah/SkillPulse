import io
from fastapi import FastAPI, UploadFile, File, HTTPException
from pypdf import PdfReader

app = FastAPI(
    title="Job–Resume Skill Matching API",
    description="Compares a resume PDF with a job description PDF and returns a match score",
    version="1.0.0"
)

# Static skill database (can later be replaced with DB or ML model)
SKILL_DB = [
    "python", "javascript", "php", "laravel", "sql", "aws",
    "react", "tailwind", "machine learning", "nlp",
    "security", "docker", "git", "java", "linux"
]


def extract_text_from_pdf(pdf_bytes: bytes) -> str:
    """
    Extract text from a PDF file (bytes) and normalize it.
    """
    try:
        reader = PdfReader(io.BytesIO(pdf_bytes))
        text = []
        for page in reader.pages:
            extracted = page.extract_text()
            if extracted:
                text.append(extracted)
        return " ".join(text).lower()
    except Exception as e:
        raise HTTPException(
            status_code=400,
            detail=f"Failed to read PDF file: {str(e)}"
        )


@app.post("/compare-job-resume")
async def compare_job_resume(
    resume: UploadFile = File(...),
    jd: UploadFile = File(...)
):
    """
    Compare resume and job description PDFs and compute skill match score.
    """

    if resume.content_type != "application/pdf" or jd.content_type != "application/pdf":
        raise HTTPException(
            status_code=415,
            detail="Only PDF files are supported"
        )

    resume_text = extract_text_from_pdf(await resume.read())
    jd_text = extract_text_from_pdf(await jd.read())

    resume_skills = {skill for skill in SKILL_DB if skill in resume_text}
    jd_skills = {skill for skill in SKILL_DB if skill in jd_text}

    matched_skills = sorted(resume_skills & jd_skills)
    missing_skills = sorted(jd_skills - resume_skills)

    score = round((len(matched_skills) / len(jd_skills)) * 100, 2) if jd_skills else 0.0

    career_map = {
        "python": "Data Scientist",
        "machine learning": "ML Engineer",
        "laravel": "Full Stack Developer",
        "react": "Frontend Engineer",
        "security": "Cybersecurity Analyst",
        "aws": "Cloud Engineer"
    }

    alternative_careers = sorted(
        {career_map[skill] for skill in resume_skills if skill in career_map}
    )

    return {
        "match_score": score,
        "matched_skills": matched_skills,
        "missing_skills": missing_skills,
        "suggested_careers": alternative_careers
    }


@app.get("/")
def health_check():
    """
    Health check endpoint.
    """
    return {"status": "FastAPI service is running"}
