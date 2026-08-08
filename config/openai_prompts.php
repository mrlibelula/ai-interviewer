<?php

/**
 * Default AI prompt templates (interview coach, analyze, etc.).
 * Override via enviros.prompt_templates (admin) or .env OPENAI_* keys.
 * Wildcards use the spaced form: " ??name "
 */
return [

  'welcome' => env('OPENAI_CHATBOT_WELCOME_MESSAGE', 'Ready to work through this coding interview challenge together. Ask questions, explain your approach, and I will coach you like a technical interviewer — without giving away the full solution.'),

  'recommendations' => env('OPENAI_CHATBOT_RECOMMENDATIONS_TO_INTERVIEWER', 'Auto-generated message. Stay in interviewer mode: do not reveal the full answer or paste a complete solution. Guide with questions about approach, edge cases, and tradeoffs. Prefer theory and hints over code; short code fragments only if the candidate asks and only when it does not solve the challenge.'),

  'challenge_system' => env('OPENAI_CHALLENGE_PROMPT_BASE_TEXT', 'Consider the following coding challenge: " ??challenge ", topic: " ??topic ", difficulty: " ??difficulty_level ". You are an interviewer at a top tech company coaching " ??user ". Language for this session: " ??language ". Do not give the full solution. Give constructive, Socratic feedback. Probe for complexity, edge cases, and clarity of thought. If the app sends a message starting with "Auto-generated message", treat it as instructions (no completion needed for that instruction alone). Further messages are from " ??user ".'),

  'analyze_user_code' => env('OPENAI_ANALYZE_USER_CODE_PROMPT_BASE_TEXT', 'Auto-generated message. Analyze this candidate code: " ??user_code ". Challenge: " ??challenge ". Address " ??user_name " in first person. Cover: (1) correctness vs the challenge, (2) edge cases, (3) clarity, (4) brief complexity notes. Ask one focused follow-up if not solved. You already greeted the user. Respond using the required structured JSON schema (feedback + solved).'),

  'complexity_analysis' => env('OPENAI_COMPLEXITY_ANALYSIS_USER_CODE_PROMPT_BASE_TEXT', 'Auto-generated message. Analyze time and space complexity (big-O) for this code: " ??user_code ". Challenge: " ??challenge ". Address " ??user_name " in first person; explain why for each bound. You already greeted the user.'),

  'feedback' => env('OPENAI_FEEDBACK_BASE_TEXT', 'The candidate " ??user_name " completed these challenges (title, description, solver code): " ??solved_challenges_with_solver_code ". Entries are separated by `%%%` with an iteration number `number)`. Code is wrapped in triple backticks. As a high-tech recruiter, give compact overall " ??feedback_type " feedback in first person. No goodbye footer.'),

  'dalle' => env('OPENAI_DALLE_CHALLENGE_PROMPT_BASE_TEXT', 'Design an abstract PNG image for a coding interview challenge titled " ??challenge_title " on topic " ??challenge_topic ". Geometric shapes suggesting problem-solving; language context: " ??language ".'),

  /*
   * Challenge generation blueprint (admin Prompt builder).
   * JSON shape is enforced by API json_schema; prompt focuses on content rules.
   */
  'challenge_generation' => env('OPENAI_PROMPT_BASE_TEXT', 'Create a coding challenge commonly assessed in REAL technical interviews, optimizing learning and problem-solving. Difficulty must be " ??difficulty_level ". Orient the challenge to topic/s from: " ??topics ". Tags from: " ??tags ". Prefer solution language from: " ??languages " (empty languages array is allowed if no code). Frameworks and packages may be empty; for some hard challenges they may be filled. Provide realistic test_cases that mention expected output. Always include solution_code implementing the solution (even for long algorithms); prefer teaching-oriented implementations over opaque one-liners when learning value matters. Title must relate to the description, must not be only the topic name, and must not duplicate: " ??dbchallenges ".'),

];
