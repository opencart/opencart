import { ready } from "./utils";

ready(() => {
    const trigger = document.querySelector(".Collapsible__trigger");

    if (trigger) {
        const content = document.querySelector(".Collapsible__content");

        trigger.addEventListener("click", (ev) => {
            if (content.classList.contains("Collapsible__content--open")) {
                content.style.height = 0;
                content.classList.remove("Collapsible__content--open");
                trigger.setAttribute("aria-expanded", "false");
            } else {
                trigger.setAttribute("aria-expanded", "true");
                content.style.transitionDuration = "150ms";
                content.style.height = `${content.scrollHeight}px`;
                content.classList.add("Collapsible__content--open");
            }
        });
    }
});
