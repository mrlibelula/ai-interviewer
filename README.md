<div align="center" style="
    display: flex; 
    justify-content: center; 
    align-items: center; 
    column-gap: 1rem;
    font-size: 1.5rem;
    line-height: 2rem;
">
    <div>
        <a href="https://libe.dev" target="_blank">
            <img src="https://libe.dev/images/libesoft.io_inv.png" width="50" style="border-radius: 0.5rem;" alt="Libe.dev Logo">
        </a>
    </div>
    <div>AI Interviewer</div>
</div>


## AI Interviewer Project

Companies are burning cash by wasting time on bad hires. Whether its different time zones, distracted interviewees, connectivity issues or just having a qualified remote candidate in front of you, if you're hiring, the old way of doing things is quickly becoming obsolete. AI Interviewer is an all-in-one, AI powered video recruitment software, that allows candidates to answer specific questions on recorded videos, so the software can analyze **key success factors**, such as:

- Sociability.
- Professionalism.
- Energy level.
- Communication skills.

This can help you in saving you time to focus only in top candidates.

By automating where appropriate and integrating AI for a more efficient approach to the many hoops that are a part of the hiring process, AI Interviewer is helping businesses ease into a new era of recruitment. 

In turn, the burden is also taken off of candidates, they need only create a job interview and share the link on their career page, social media, or jobs site, and the AI Interviewer platform takes care of the rest. Creating symbiosis between employees and employers, AI Interviewer will help turn a page on the “Great Resignation” and write a new chapter for hiring.

## App Features

- Conduct interviews
- Provide feedback
- Answer user questions

## Resources

- [UI model](https://dribbble.com/shots/22237746-Intervio-AI-Interview-Dashboard#)
- [OpenAI Client repo](https://github.com/openai-php/client)
- [OpenAI for Laravel](https://laravel-news.com/openai-for-laravel)
- [Laracasts OpenAI](https://laracasts.com/series/fun-with-openai-and-laravel/episodes/1)

## Bugs

- in "chatcmpl-90VIqIxcWaTSM81Yg7p2Pkt6wqYvm" (TALL Stack CRUD Application) the solution code (html) is being executed inside code frame (see screenshot 33)
- Selected topic: "Git", doesn't comply requirements for completion 🙈.

## Next task

- Add "language" as a variable for prompting new challenges, along with topics and difficulty.
- How can we determine if a text is code (any language) or just a string.
- When placing the wildcard for topic/s, include also the selected topic subtree.
- Check for same challenges before inserting into db.
- Incase of response error, search for another challange.
- The process shall be: get completion, analyze it/them, then import it/them to DB via importer section. Should also be able to select status and visibility before importing.
- Wildcards cutomization in prompt buildChallengePrompt().
- JSON cutomization in prompt loadBlueprintDataAndStoreToDB(), buildJson(), and also affected buildJsonArrays() that depends on $this->build_json.
- Code component word-wrap toggle feature.