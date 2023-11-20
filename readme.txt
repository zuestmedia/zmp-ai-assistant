=== ZMP AI Assistant ===
Contributors: zuestmedia
Stable tag: 1.0.0
Tags: gpt, open ai, chat gpt, content generator, image generator, zmplugin
Requires at least: 4.7
Tested up to: 6.4
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This Plugin adds the AI Assistant to the edit-post-screen, to directly create content and images with artificial intelligence, while working in WordPress editor.

== Description ==

[ZMP AI Assistant](https://zuestmedia.com/plugins/) extends the WordPress edit-post-screen with the AI Assistant to directly create content and images with artificial intelligence.

An API key from Open AI is required to use the AI Assistant. You can get a key in your user account at [Open AI](https://platform.openai.com/api-keys).

The AI Assistant accesses the API interface of Open AI to generate texts and images.

Data is sent to and retrieved from the following interfaces: 

- https://api.openai.com/v1/chat/completions
- https://api.openai.com/v1/images/generations
- https://api.openai.com/v1/models

Here you can find all information about the terms of use and data protection of Open AI: https://openai.com/policies. 

== Frequently Asked Questions ==

= How to install ZMP AI Assistant? =

To install ZMP AI Assistant, go to Dashboard >> Plugins >> Add new. Then enter "ZMP AI Assistant" in the search field, click on install and activate.

= Does ZMP AI Assistant work as standalone? =

No. ZMP AI Assistant is an extension of ZMPlugin.

== Changelog ==

= 1.0.0 =
* Update: Ready for release on wordrpess.org
* Update: Readme text
* Update: Replaced legacy completions API with actual chat completions API
* Update: Default model settings change to gpt-4
* Update: Image API now with dall-e-3
* Remove: Legacy edit API

= 0.9.4 =
* Fix: Style Error in Form

= 0.9.3 =
* Update: escaping & translations added
* Update: CI/CD Routines
* Fix: Minor bug fixes

= 0.9.2 =
* Update: AIForm finished
* New: PrepareTemplate to filter and prepare form inputs and variables
* New: Copy and paste selection to title or editor
* New: Upload generated images to media library
* Fix: Settings Form finished

= 0.9.1 =
* Update: All checks integrated, next step -> prepare settings array eg prompt with varialbes like __title__ or __postmeta__ usw...

= 0.9.0 =
* Initial release of ZMP AI Assistant

= 0.8.4 =
* Fix: credential check was wrong...

= 0.8.3 =
* Update: Form and APIS are fully functional, still needs some error checks and animations to finish

= 0.8.2 =
* Update: Form is functional and has all needed settings except "stop"

= 0.8.1 =
* New: Connected to GPT-3 API

= 0.8.0 =
* Initial beta release of ZMP AI Assistant

== Copyright ==
ZMP AI Assistant WordPress Plugin, Copyright 2023 zuestmedia.com
ZMP AI Assistant is distributed under the terms of the GNU GPL