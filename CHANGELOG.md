rss-bridge Changelog
===

Alpha 0.2
===
 * Refactoring to add composer, autoload, namespace and better stored directory
 * Some change to prepare futur
 * Redefined some visibility to avoid user mistake
 * Bridge/Cache/Format now share a same Adapter
 * Cache strategy change, now HTML source is stored to share cache between formats
 * Intensive adding comments
 * New annotation parser
 * Unstable.

Alpha 0.1
===
 * Firt tagged version.
 * Includes refactoring.
 * Unstable.

Current development version
===
 * Corrected GoogleBridge (URI extraction was incorrect)
 * Corrected ATOM format:
   * mime-type was incorrect
   * Hyperlinks were not clickable.
   * non-UTF8 characters are now properly filtered.
 * Corrected HTML format output:
   * Hyperlinks were not clickable.
 * Corrected error message when SimpleHtmlDom library is not installed.
 * Added changelog.
 