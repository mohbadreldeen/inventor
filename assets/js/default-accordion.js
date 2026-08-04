(() => {
  // src/js/utils/runtime.js
  function onDomReady(callback) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", callback, { once: true });
      return;
    }
    callback();
  }

  // src/js/default-accordion.js
  (function() {
    "use strict";
    function bindAccordion(accordion) {
      if (!accordion || accordion.dataset.defaultAccordionBound === "1") {
        return;
      }
      accordion.dataset.defaultAccordionBound = "1";
      var accordionImages = Array.from(document.querySelectorAll(".accordion-image"));
      function updateAccordionImages(activeIndex) {
        accordionImages.forEach(function(image) {
          var isActive = image.getAttribute("data-accordion-index") === String(activeIndex);
          if (isActive) {
            image.style.display = "block";
            requestAnimationFrame(function() {
              image.classList.add("is-active");
            });
            return;
          }
          image.classList.remove("is-active");
          image.style.display = "none";
        });
      }
      accordion.querySelectorAll(".e-n-accordion-item > summary").forEach(function(summary, index) {
        summary.addEventListener("click", function() {
          var activeIndex = summary.getAttribute("data-accordion-index") || String(index + 1);
          updateAccordionImages(activeIndex);
        });
      });
      var activeSummary = accordion.querySelector(".e-n-accordion-item[open] > summary") || accordion.querySelector(".e-n-accordion-item > summary[data-accordion-index='1']");
      if (activeSummary) {
        updateAccordionImages(activeSummary.getAttribute("data-accordion-index") || "1");
      }
    }
    function setupDefaultAccordion() {
      document.querySelectorAll(".default-accordion").forEach(bindAccordion);
    }
    onDomReady(setupDefaultAccordion);
  })();
})();
//# sourceMappingURL=default-accordion.js.map
