import { onDomReady, onElementorReady } from "./utils/runtime.js";

(function () {
	"use strict";

	var ROOT_SELECTOR = ".inventor-support-article-search";

	function getApiConfig() {
		if (!window.wpInventorSupportArticleSearch) {
			return null;
		}

		var config = window.wpInventorSupportArticleSearch;
		if (!config.ajaxUrl || !config.nonce) {
			return null;
		}

		return config;
	}

	function toInt(value, fallback) {
		var parsed = Number.parseInt(value, 10);
		return Number.isFinite(parsed) ? parsed : fallback;
	}

	function escapeHtml(text) {
		return String(text || "")
			.replace(/&/g, "&amp;")
			.replace(/</g, "&lt;")
			.replace(/>/g, "&gt;")
			.replace(/\"/g, "&quot;")
			.replace(/'/g, "&#039;");
	}

	function setResultsHtml(resultsWrap, html) {
		resultsWrap.innerHTML = html;
		resultsWrap.hidden = !html;
	}

	function renderItems(resultsWrap, items, noResultsText) {
		if (!items || !items.length) {
			setResultsHtml(resultsWrap, '<div class="inventor-support-article-search__empty">' + escapeHtml(noResultsText) + "</div>");
			return;
		}

		var html = '<ul class="inventor-support-article-search__list">';

		items.forEach(function (item) {
			html += '<li class="inventor-support-article-search__item">';
			html += '<a class="inventor-support-article-search__link" href="' + escapeHtml(item.url) + '">' + escapeHtml(item.title) + "</a>";
			if (item.excerpt) {
				html += '<div class="inventor-support-article-search__excerpt">' + escapeHtml(item.excerpt) + "</div>";
			}
			html += "</li>";
		});

		html += "</ul>";
		setResultsHtml(resultsWrap, html);
	}

	function setupWidget(root, apiConfig) {
		if (!root || root.dataset.searchBound === "1") {
			return;
		}

		var field = root.querySelector(".inventor-support-article-search__field");
		var input = root.querySelector(".inventor-support-article-search__input");
		var resultsWrap = root.querySelector(".inventor-support-article-search__results");

		if (!field || !input || !resultsWrap) {
			return;
		}

		setResultsHtml(resultsWrap, "");

		var minChars = Math.max(1, toInt(root.dataset.minChars, 2));
		var limit = Math.max(1, Math.min(20, toInt(root.dataset.limit, 8)));
		var noResultsText = root.dataset.noResults || "No support articles found.";
		var debounceTimer = null;
		var requestToken = 0;

		function runSearch() {
			var query = (input.value || "").trim();

			if (query.length < minChars) {
				setResultsHtml(resultsWrap, "");
				return;
			}

			requestToken += 1;
			var currentToken = requestToken;

			setResultsHtml(resultsWrap, '<div class="inventor-support-article-search__loading">Searching...</div>');

			var body = new URLSearchParams();
			body.append("action", "wp_inventor_support_article_search");
			body.append("nonce", apiConfig.nonce);
			body.append("query", query);
			body.append("limit", String(limit));

			fetch(apiConfig.ajaxUrl, {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
				},
				body: body.toString(),
				credentials: "same-origin"
			})
				.then(function (response) {
					return response.json();
				})
				.then(function (payload) {
					if (currentToken !== requestToken) {
						return;
					}

					if (!payload || !payload.success || !payload.data) {
						renderItems(resultsWrap, [], noResultsText);
						return;
					}

					renderItems(resultsWrap, payload.data.items || [], noResultsText);
				})
				.catch(function () {
					if (currentToken !== requestToken) {
						return;
					}
					renderItems(resultsWrap, [], noResultsText);
				});
		}

		field.addEventListener("submit", function (event) {
			event.preventDefault();
			runSearch();
		});

		input.addEventListener("input", function () {
			if (debounceTimer) {
				window.clearTimeout(debounceTimer);
			}

			debounceTimer = window.setTimeout(runSearch, 240);
		});

		root.dataset.searchBound = "1";
	}

	function initAll(scope) {
		var root = scope || document;
		var apiConfig = getApiConfig();

		if (!apiConfig) {
			return;
		}

		if (root.matches && root.matches(ROOT_SELECTOR)) {
			setupWidget(root, apiConfig);
		}

		if (root.querySelectorAll) {
			root.querySelectorAll(ROOT_SELECTOR).forEach(function (widget) {
				setupWidget(widget, apiConfig);
			});
		}
	}

	function boot() {
		initAll(document);
		onElementorReady(function (root) {
			initAll(root);
		});
	}

	onDomReady(boot);
})();
