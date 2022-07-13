"use strict";

/**
 * @link https://www.npmjs.com/org/wordpress
 */
wp.domReady(() => {
  const { removeEditorPanel } = wp.data.dispatch("core/edit-post");

  /* Remove featured image panel from sidebar. */
  // removeEditorPanel("taxonomy-panel-category");       // category
  // removeEditorPanel("taxonomy-panel-TAXONOMY-NAME");  // custom taxonomy
  removeEditorPanel("taxonomy-panel-post_tag"); // tags
  removeEditorPanel("featured-image"); // featured image
  // removeEditorPanel("post-link");                     // permalink
  // removeEditorPanel("page-attributes");               // page attributes
  removeEditorPanel("post-excerpt"); // Excerpt
  removeEditorPanel("discussion-panel"); // Discussion
});
