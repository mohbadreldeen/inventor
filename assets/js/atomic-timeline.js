(() => {
  // src/js/utils/runtime.js
  function onDomReady(callback) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", callback, { once: true });
      return;
    }
    callback();
  }
  function onElementorReady(callback) {
    if (!window.elementorFrontend || !window.elementorFrontend.hooks) {
      return;
    }
    window.elementorFrontend.hooks.addAction("frontend/element_ready/global", function($scope) {
      callback($scope && $scope[0] ? $scope[0] : document);
    });
  }

  // src/js/atomic-timeline.js
  (function() {
    "use strict";
    var ACTIVE_CLASS = "is-active";
    var BEFORE_CLASS = "is-before-active";
    var AFTER_CLASS = "is-after-active";
    function getItems(container) {
      return Array.from(
        container.querySelectorAll(":scope > [data-element_type='e-inventor-timeline-item']")
      );
    }
    function setActiveItem(container, index) {
      var items = getItems(container);
      var maxIndex = items.length - 1;
      if (maxIndex < 0) {
        return;
      }
      var safeIndex = Math.max(0, Math.min(index, maxIndex));
      var progress = maxIndex === 0 ? 0 : safeIndex / maxIndex * 100;
      container.style.setProperty("--timeline-progress", progress + "%");
      container.setAttribute("data-active-index", String(safeIndex));
      items.forEach(function(item, itemIndex) {
        var isActive = itemIndex === safeIndex;
        var isBefore = itemIndex < safeIndex;
        item.classList.toggle(ACTIVE_CLASS, isActive);
        item.classList.toggle(BEFORE_CLASS, isBefore);
        item.classList.toggle(AFTER_CLASS, itemIndex > safeIndex);
        item.setAttribute("data-timeline-index", String(itemIndex + 1));
        item.setAttribute("aria-current", isActive ? "step" : "false");
      });
    }
    function bindItemEvents(container) {
      var items = getItems(container);
      if (!items.length) {
        return;
      }
      items.forEach(function(item) {
        if (item.dataset.atomicTimelineBound === "1") {
          return;
        }
        item.addEventListener("click", function() {
          var currentItems = getItems(container);
          var index = currentItems.indexOf(item);
          if (index < 0) {
            return;
          }
          setActiveItem(container, index);
        });
        item.addEventListener("keydown", function(event) {
          var currentItems = getItems(container);
          var index = currentItems.indexOf(item);
          if (!currentItems.length || index < 0) {
            return;
          }
          var nextIndex = index;
          if (event.key === "ArrowRight" || event.key === "ArrowDown") {
            event.preventDefault();
            nextIndex = (index + 1) % currentItems.length;
          } else if (event.key === "ArrowLeft" || event.key === "ArrowUp") {
            event.preventDefault();
            nextIndex = (index - 1 + currentItems.length) % currentItems.length;
          } else if (event.key === "Home") {
            event.preventDefault();
            nextIndex = 0;
          } else if (event.key === "End") {
            event.preventDefault();
            nextIndex = currentItems.length - 1;
          } else {
            return;
          }
          setActiveItem(container, nextIndex);
          currentItems[nextIndex].focus();
        });
        item.setAttribute("tabindex", "0");
        item.dataset.atomicTimelineBound = "1";
      });
    }
    function setupTimeline(container) {
      if (!container) {
        return;
      }
      container.classList.add("inventor-atomic-timeline--ready");
      container.classList.add("inventor-atomic-timeline--flow");
      bindItemEvents(container);
      var defaultIndex = parseInt(container.getAttribute("data-default-index") || "0", 10);
      if (!Number.isFinite(defaultIndex)) {
        defaultIndex = 0;
      }
      setActiveItem(container, defaultIndex);
    }
    function initAll(root) {
      var scope = root || document;
      if (scope.matches && scope.matches("[data-element_type='e-inventor-timeline']")) {
        setupTimeline(scope);
      }
      scope.querySelectorAll("[data-element_type='e-inventor-timeline']").forEach(setupTimeline);
    }
    function boot() {
      initAll(document);
      onElementorReady(function(root) {
        initAll(root);
      });
    }
    onDomReady(boot);
  })();
})();
//# sourceMappingURL=atomic-timeline.js.map
