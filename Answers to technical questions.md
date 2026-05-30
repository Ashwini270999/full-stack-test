# Answers to Technical Questions

### 1. How long did you spend on the coding test? What would you add to your solution if you had more time?
* **Time Spent:** I spent approximately 2 and a half days working on this evaluation. This included setting up the MySQL database layer, creating a reliable PHP MVC folder architecture to handle slide records, implementing the administrative CRUD dashboards, and refining the frontend interface for pixel-perfect responsiveness matching both desktop and mobile target mockups.

* **Production-Grade Features Already Implemented:**
    1. **Robust Validation Layer (Create & Edit):** Implemented thorough data sanitization using `trim()` alongside mandatory required string tracking. I also added strict browser-side validation blocks (`required` and explicit asset input `accept` parameters) to guide user entries.
    2. **Deep Binary Image Inspections:** Implemented server-side security using PHP's `finfo` extension to analyze the underlying binary magic bytes of file uploads, safely blocking malicious or corrupted injections from breaking layouts regardless of their renamed file extension.
    3. **Atomic File Swapping Operations:** Built a safe file-swapping pipeline during record adjustments that ensures older slide assets are *only* pruned from disk (`unlink()`) once the new image upload and MySQL database transactions succeed completely. If a transaction fails, it auto-rolls back file transfers to save space and prevent broken carousel links.
    4. **UX Destructive Safeguards:** Wired JavaScript confirmation dialog interceptors on all dashboard element removal controls to protect against unintended record purging.
    5. **Separation of Concerns:** Externalized frontend interactive JavaScript controllers into detached component assets (`main.js`), eliminating script parsing overhead from server-side markup trees.
    6. **De-coupled Asset Management:** Externalized all custom presentation layers into a dedicated stylesheet layout (`style.css`). This strict separation reduces template overhead, enables native browser asset caching, and radically maximizes Cumulative Layout Shift (CLS) performance scores.

* **What I would add with more time:**
    1. **Architecture Upgrade (React & Framework Integration):** If given more time, I would refactor the front-end architecture of this module into a single, cohesive **React.js application**. Instead of server-side PHP loops rendering the static containers, I would create a highly optimized, state-driven reusable component layout. It would dynamically pull database slides through a lightweight **asynchronous Ajax API layer** hosted on an optimized **Laravel**, **CodeIgniter**, or **Yii** REST API controller endpoint. This structural separation would completely eliminate layout flickering, enable smooth fluid layout translations, and drastically decouple the front-end presentation from our core server layer.

---

### 2. How would you track down a performance issue in production? Have you ever had to do this?
Yes, tracking down latency and system bottlenecks in production requires a highly structured, multi-tier diagnostic approach across the entire stack:

* **Database Tier (PostgreSQL / MySQL):** I look for unoptimized queries, missing foreign key indexes, or heavy sequential table scans. In PostgreSQL, I run `EXPLAIN ANALYZE` on slower queries to track execution tree costs. In MySQL, I utilize the Slow Query Log and implement connection pooling to handle heavy user traffic efficiently.
* **Backend Application Tier (Laravel / CodeIgniter / Yii):** I profile execution timelines using specialized ecosystem tooling (e.g., Laravel Telescope, debug bars, or Xdebug profiling logs). Common bottlenecks often include "N+1 query problems" which I resolve using eager loading. I optimize performance by caching highly repetitive, static data queries using Redis or Memcached structures.
* **Frontend Tier (React.js / Ajax / Asset Pipelines):** For client-side latency, I audit network operations in Chrome DevTools to check asynchronous Ajax payload sizes. In React, I prevent unnecessary re-renders of complex visual structures by optimizing the component architecture with hooks like `useMemo`, `useCallback`, and implementing lazy loading for large code splittings.
* **Workflow & Error Isolation:** I track application issues through centralized error logging platforms and align bug resolutions systematically within our team's **Jira** sprint tracking boards.

---

### 3. Please describe yourself using JSON.
```json
{
  "name": "Ashwini",
  "role": "Full Stack Developer",
  "professional_profile": {
    "philosophy": "Detail-oriented engineer focused on writing highly optimized, clean, and reusable code structures following robust DRY and SOLID design principles.",
    "methodologies": ["Agile/Scrum", "MVC Architecture", "Object-Oriented Programming (OOP)"]
  },
  "technical_skills": {
    "backend_frameworks": ["Laravel", "CodeIgniter", "Yii", "PHP (Core & OOP)"],
    "frontend_ecosystem": ["React.js", "JavaScript (ES6+)", "jQuery", "Ajax", "Bootstrap 5", "HTML5/CSS3"],
    "databases_and_storage": ["PostgreSQL", "MySQL"],
    "version_control": ["Git", "SVN"],
    "project_management_tools": ["JIRA"]
  },
  "engineering_strengths": {
    "optimization": "Database indexing, query profiling, caching strategies, and minifying asset payloads to enhance Core Web Vitals.",
    "reusability": "Designing generic database service layers, building reusable React components, and abstracting shared utility helpers.",
    "attention_to_detail": "Thorough edge-case testing, cross-browser CSS responsiveness, and strict adherence to Figma/design layout mockups."
  }
}