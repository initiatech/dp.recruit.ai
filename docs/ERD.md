# Entity Relationship Diagram

This document provides a visual overview of the database schema using a Mermaid ERD.

*Note: This file contains the Mermaid source code for the diagram. A separate process can be used to export this to a PNG or SVG if a static image is required.*

```mermaid
erDiagram
    candidates ||--o{ conversations : "has"
    jobs ||--o{ conversations : "has"
    users ||--o{ recruiter_notes : "writes"
    users ||--o{ audit_logs : "performs"

    conversations {
        char(36) id PK
        int candidate_id FK
        int job_id FK
        enum status
        timestamp started_at
    }

    conversations ||--o{ messages : "contains"
    conversations ||--o{ interview_progress : "tracks"
    conversations ||--o{ recruiter_notes : "has"
    conversations ||--|| heat_scores : "results in"

    candidates }o--o| candidate_labels : "is tagged via"
    labels }o--o| candidate_labels : "tags"

    candidates {
        int id PK
        varchar full_name
        varchar email
    }

    jobs {
        int id PK
        varchar title
    }

    messages {
        int id PK
        char(36) conversation_id FK
        enum sender
        text content_text
    }

    interview_progress {
        int id PK
        char(36) conversation_id FK
        varchar field_key
    }

    heat_scores {
        int id PK
        char(36) conversation_id FK
        int score
    }

    recruiter_notes {
        int id PK
        char(36) conversation_id FK
        int user_id FK
    }

    users {
        int id PK
        varchar full_name
        varchar email
    }

    labels {
        int id PK
        varchar name
    }

    candidate_labels {
        int candidate_id PK, FK
        int label_id PK, FK
    }

    audit_logs {
        int id PK
        int actor_user_id FK
        varchar entity_type
        varchar entity_id
    }
```
