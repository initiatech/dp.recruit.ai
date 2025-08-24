# AI Model Schemas

This document defines the JSON schemas the AI model is expected to adhere to for its responses. Enforcing these schemas is critical for the stability of the application.

## `MESSAGE` (Candidate-Facing Response)

This is the standard schema for messages sent back to the candidate during the interview. The AI's response should be a single JSON object matching this structure.

```json
{
  "text": "string",
  "next_required_field": "string | null",
  "finalize": "boolean"
}
```

-   **`text`** (string, required): The message to be displayed to the candidate. This must be in Hebrew and RTL-friendly.
-   **`next_required_field`** (string | null, required): The key of the next piece of information the AI is trying to collect (e.g., "experience_years"). This helps the client application understand the interview's state. It should be `null` if the AI is not actively seeking a specific field or if the interview is over.
-   **`finalize`** (boolean, required): Set to `true` only when the interview is complete and the conversation should be terminated. When `true`, the client application should display an end-of-interview message and disable further input.

---

## `INTERVIEW_SUMMARY_JSON` (Recruiter-Facing Summary)

This schema is for the final summary object generated after the interview is completed. This JSON object is **for internal use only** (sent to the recruiter dashboard and webhooks) and must **never** be exposed to the candidate.

```json
{
  "candidate": {
    "id": "integer",
    "full_name": "string",
    "email": "string",
    "phone": "string"
  },
  "job": {
    "id": "integer",
    "title": "string"
  },
  "conversation": {
    "id": "string",
    "started_at": "string (ISO-8601)",
    "closed_at": "string (ISO-8601)"
  },
  "collected_fields": {
    "summary": "string",
    "experience_years": "string | integer",
    "...": "..."
  },
  "heat_score": {
    "total": "integer",
    "breakdown": {
      "fit": "integer",
      "detail": "integer",
      "responsiveness": "integer"
    },
    "rationale": "string"
  },
  "decision_recommendation": {
    "status": "string (accepted | rejected | more_info)",
    "reason": "string"
  },
  "full_transcript": [
    {
      "sender": "string (ai|candidate|system)",
      "text": "string",
      "timestamp": "string (ISO-8601)"
    }
  ]
}
```
