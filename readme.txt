=== S2B AI Assistant - ChatBot, ChatGPT, OpenAI, Content & Image Generator ===
Name: S2B AI Assistant
Contributors: oc3dots 
Tags: chatbot, gpt,  AI, content generator,  openai
Requires at least: 5.6 
Tested up to: 6.6
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 1.6.3

Create multiple AI chatbots with different styles and behavior, content aware feature  of bot,   generate content and images using ChatGPT API ...

== Description ==

Develop multiple AI chatbots with different styles and behaviors on different pages of your website, including using content-aware functionality [OpenAI Assistant API](https://platform.openai.com/docs/assistants/overview). You can record and save chat conversations between users and the chatbot. You can personalize the appearance of the chatbot: colors, styles, text; Personalize its position on the screen, window size, chatbot behavior by choosing: model, instruction, temperature, tokens, etc. You have the option to choose whether the chatbot will be visible only to registered visitors or not. The plugin allows you to update models directly from OpenAI and independently choose the model to use. Additionally, it allows you to create/modify content and images as well as generate code using the ChatGPT API. The API provides access to large language models.
With this plugin, you can not only edit and generate code, but also perform various content manipulations using OpenAI [ChatGPT](https://chat.openai.com) . You can use plugin for generate images using [Dall-E2](https://openai.com/dall-e-2) and [Dall-E3](https://openai.com/dall-e-3) AI systems.  One of the features is the ability to create a database of instructions, which you can easily refer back to whenever needed. Moreover, you have the flexibility to choose from a wide range of models available in the OpenAI ChatBot API, ensuring that your requests are tailored to your specific needs. 
S2B AI Assistant is plugin for WordPress powered by any model you can choose from OpenAI API platform https://platform.openai.com/docs/models . 
You can log conversations between AI chatbot and visitors of your website. 

### Features
* AI powered chatbot
* Multiple different chatbots with different style and behavior for different website pages
* Content aware AI chatbot using Assistant API, better use gpt-4-turbo-preview, gpt-4
* Conversation Logging. Recording and saving chat interactions between users and the chatbot
* Personalize the appearance of the chatbot: colors, styles, text
* Personalize view of chatbot using custom css (additional feature for each chatbot)
* Personalize behavior of the chatbot: model, instruction, temperature, tokens, etc.
* Option to choose if the chatbot is only visible to registered visitors or not
* Is possible to select position, size of chatbot's window
* Dynamic update of models directly from OpenAI
* Select any model which to use in plugin
* Plugin can generate images 
* Correct any grammatical errors
* Rewrite sentences
* Summarizing
* Finish the sentence
* Improve your writing skills
* Engaging questions
* Answering All Inquiries
* Create product descriptions
* Intent Recognition
* Code Understanding
* Select any model you want from ChatGpt
* Create a blog intro for your next article
* Create blog outlines
* Change the tone of your sentences
* Create brand names, startup ideas and slogans
* Generate a list of marketing ideas
* Brand story
* Create ad copy for Adwords and Facebook
* Create landing page content
* Change access to different functions of plugin for different user roles

 



== Installation ==

### Quick setup:
-Open account at [OpenAI](https://platform.openai.com/signup) and get API key
-Go to <YOUR_WEBSITE_URL>/wp-admin/admin.php?page=s2baia_settings page of this plugin. Input copied API key in the 'Open AI Key:' field of the ChatGPT tab. You can copy API key on [api keys page](https://platform.openai.com/account/api-keys) 
-Switch to Models tab. Click on the 'Refresh from OpenAI' button. It updates models from OpenAI server. Select Models which you want to use.  Select the Edit checkbox for models that will be used with chatbots and text manipulation. Select the Expert checkbox for models to be used in the Expert playground. Finally click the Save button. You can now use the S2B AI Assistant plugin.
-You can customize the position, appearance, behavior of the chatbot on the ChatBot configuration page <YOUR_WEBSITE_URL>/wp-admin/admin.php?page=s2baia_chatbot. On this page, you can create many chatbots that differ in their appearance, position and behavior.
-To get the chatbot operational, simply insert the shortcode [s2baia_chatbot] to any post or page
-If you want to use different chatbots on different pages, add a shortcode in the format [s2baia_chatbot bot_id=generated_hashcode] to any page or post.
-To add content aware feature using OpenAI Assistant API you need to go to Chatbot page, then click on Assistant API and follows 3 steps:
1.Upload file that will be used as knowledge base by ChatGPT.
2.Create new Assistant by filling instruction and selecting model. For details see below in Content aware feature section below.
3.Add shortcode [s2baia_chatbot bot_id=assistant] to any page, to display Assistant. We tested this feature with different models and found that the best result are when using  gpt-4-turbo-preview, gpt-4 and gpt-4-turbo models. Please read [this](https://soft2business.com/how-to-create-content-aware-chat-bot/) and [this](https://soft2business.com/how-to-create-ai-assistants-manually/) articles for detailed information.
-Additionally you can create many Assistants inside OpenAI Assistant dashboard and link them with our plugin. Read this [article](https://soft2business.com/how-to-create-ai-assistants-manually/) on how to do it.


### Detailed setup and configuration:

### Initial configuration.

1. Go to Settings page <YOUR_WEBSITE_URL>/wp-admin/admin.php?page=s2baia_settings. In the ChatGPT tab fill your API key in the 'Open AI Key:' . You can generate API key on this page: https://platform.openai.com/account/api-keys
2. You can fill other fields on this tab or leave them by default.


### Chat GPT Models configuration. 

3.Switch to Models tab. Click on the 'Refresh from OpenAI' button. It updates models from OpenAI server.
4.Select Models which you want to use in different functions of our plugin. Finally click 'Save' button.  Select the Edit checkbox for models that will be used with chatbots and text manipulation. Select the Expert checkbox for models to be used in the Expert playground. Remember that not all models are suitable for chatbots and word processing. That means you can get error response like this one 'This is not a chat model and thus not supported in the v1/chat/completions endpoint. Did you mean to use v1/completions?' when you try to use them. As of today's date (July 2024)  we recommend to select gpt-3.5-turbo, gpt-3.5-turbo-16k-0613, gpt-4 versions, gpt-4-turbo-preview  and gpt-4-turbo versions.   You can play with selection and deselection models to find best that fit to your needs. 
For more information about selecting models [read](https://platform.openai.com/docs/models/)

### AI Chatbot setup.
Open Chatbot menu page in wp-admin. You can see such tabs: 
General, Styles, Chatbots,Assitant API, Assistants, Logs and Support. On General, Styles You can customize default behavior, appearance and view of chatbots. In particular you can select ChatBot position, ChatBot size, model, colors of chatbot's elements etc.
If you want to use different chatbots on different pages then create bot in Chatbots tab and then put shortcode in format [s2baia_chatbot bot_id=automatically_generated_hashcode] into any page or post. In addition, the plugin allows you to create AI Assistants using the  [Assistants API](https://platform.openai.com/docs/assistants/overview). You can create Assistants in Assistant API and or in Assitants tabs. Read below on how to do this. 

Notice! Chatbot view parameters like position, size, colors are affected by caching. When you found that you have changed chatbot parameters, but view has not changed then try to clear cache then reload page.

### Content aware feature for AI chatbot.
OpenAI introduced new [Assistant API](https://platform.openai.com/docs/assistants/tools/file-search) which allows it automatically to parse and chunk uploaded documents, to create and to store the embeddings, and use both vector and keyword search to retrieve relevant content to answer user queries. We implemented File Search API feature in our pugin. Before using this on your website, you should remember some important tips:
1.It is Beta feature and not yet tested carefully. Thus it can cause unpredicted behavior. Therefore, we cannot guarantee the chatbot's responses.
2.OpenAI charges additionally besides used conversational tokens. At the moment of release version 1.5.8 of this plugin it costs  $0.10 / GB of vector-storage per day (1 GB free) + used tokens during conversation.  Please [observe](https://openai.com/pricing)  to be informed about pricing updates.
3.The effectiveness and accuracy of the bot's responses depends on the system instructions provided and the models used in the prompts. For detailed information please read [article](https://soft2business.com/how-to-create-content-aware-chat-bot/)
To setup content aware chatbot using Assistant API you need to open page Chatbot and select Tab "Assistant API". Then you need to perform 3 steps:
1.Upload file that will be used as knowledge base by ChatGPT.
2.Create new Assistant by filling instruction, selecting model and timeout. 
3.Add the shortcode [s2baia_chatbot bot_id="assistant"] to any page you want.
Additionally you can create  many assistants manually in the [Assistants OpenAI dashboard](https://platform.openai.com/assistants/) and link them to our plugin. For this you need to go to [Assistants OpenAI dashboard](https://platform.openai.com/assistants/) and create assistants there. Then copy ID of created assistant. After that, you need to create a new assistant in the plugin Assistants tab.Finally, paste the ID in the Assistant ID field.

Alternatively you can use Completion API [to create content aware chatbot](https://soft2business.com/how-to-create-content-aware-chat-bot/#content_aware_completion_api_use). This approach is better if you have a small amount of text that can be used as context. For medium to large amounts of text, the Assistant API is preferable.


### Image generation setup.
Image generation feature requires only API key. It is ready to use by default. You can configure generation options on the Image Generation tab. On the same tab you can generate and store new images using Dall-e-2 and Dall-e-3 models.

### Instruction setup.


Select page types you want to display metabox of the S2B AI assistant plugin at the configuration page.
Select models you want to use in the S2B AI assistant plugin.
The S2B AI assistant plugin enables you to create and manage instructions for ChatGPT. You can store these instructions in a database and use them in your requests. To access the instructions, simply switch to the "Instructions" tab. From there, you can add, edit, delete, enable, or disable instructions. Additionally, you can search for specific instructions by entering text in the "Search Instructions" input box.
For additional instructions and ideas, please visit our [page](https://soft2business.com/chatgpt-instructions/)   or [ OpenAI best practice page](https://platform.openai.com/docs/guides/gpt-best-practices) 




### Post types setup.
The S2B AI Assistant plugin is accessible by default in the metabox displayed beneath the edit area for two post and page types. However, if desired, you have the option to enable it on other post types' edit pages. To do so, navigate to the General setup tab and select the desired post types.



== How to use plugin ==

-Open account at [OpenAI](https://platform.openai.com/signup) and get API key
-Go to Settings page YOUR_WEBSITE_URL/wp-admin/admin.php?page=s2baia_settings page of this plugin. Paste API key in the 'Open AI Key:' field of the ChatGPT tab.  
-Switch to Models tab. Click on the 'Refresh from OpenAI' button. It updates models from OpenAI server. Select Models which you want to use.  Select the Edit checkbox for models that will be used with chatbots and text manipulation. Select the Expert checkbox for models to be used in the Expert playground. Finally click the Save button. Now you can now  the S2B AI Assistant plugin.
-You can create many chatbots on the Chatbot page YOUR_WEBSITE_URL/wp-admin/admin.php?page=s2baia_chatbot page of this plugin.
-To get the chatbot operational, insert the shortcode in one of the following formats [s2baia_chatbot], [s2baia_chatbot bot_id=automatically_generated_hashcode] (where automatic_generated_hashcode is the chatbot hashcode that is automatically assigned when the chatbot is created) or [s2baia_chatbot bot_id=assistant] into any page or post. First format allows to exploit chatbot that uses Chat Completion API. This is default chatbot type. The second format allows for both types of chatbots using the Chat Completion API and the Assistant API. Third format of shortcode allows to  use the automatically generated AI Assistant directly from our plugin. You can use all 3 types of chatbots on different pages or posts. You can create as many different chatbots as you like with the format [s2baia_chatbot bot_id=automatically_generated_hashcode]. You can also place a chatbot with the same shortcode on different pages and/or posts.

-To create a content-aware chatbot using OpenAI Assistant API you have two options.  First option is to generate AI Assistnt directly from our plugin. For doing this you need to click on Assistant API tab and follows 3 steps:
1.Upload file that will be used as knowledge base by ChatGPT.
2.Create new Assistant by filling instruction and selecting model. For details see [article](https://soft2business.com/how-to-create-content-aware-chat-bot/).
3.Add  shortcode [s2baia_chatbot bot_id="assistant"] to any page you want
Additionally, you can read  [article](https://soft2business.com/how-to-create-content-aware-chat-bot/) about how to better configure content aware  chatbot.

The second option to create an AI Assistant is to do it through the OpenI Assistants page and then link to our plugin. Read this [article](https://soft2business.com/how-to-create-ai-assistants-manually/) for more details.


-For image generation open Image page in /wp-admin side. There you can generate images, using Dall-e-2 or Dall-e-3 models and store them into Media library.

-For using content feature please open any type of post edition page. Then scroll down to the S2B AI Assistant metabox. There, you can enter text into the 'Text to be changed' input field. After that, you can manually input your instructions. Additionally, you can select any previously saved instructions in the database. Also you can select other parameters such as the model, temperature, and maximum length of the request and response text. Finally, click the Send button. If everything goes well, you will receive a response in the Result textarea.

For those with more in-depth knowledge of using ChatGPT, we offer the Expert tab.

For additional information regarding prompts see [this page](https://platform.openai.com/docs/guides/gpt-best-practices) 

== Chatbot view modifications ==

Besides of styling chatbot in configuration pages you also can use some predefined views or view modifications. In version 1.6.1 we introduce modalless view which allows you to put chatbot as part of web page without showing modal window. To use this function you can add shortcode [s2baia_chatbot bot_id=BOT_HASH_CODE  view=embedded] where BOT_HASH_CODE is hashcode generated automatically when you create new Assistant or Chat Bot. This hash code is displayed in hash column of table with chat bot or assistants list. Also it is possible to use such simple form of shortcode [s2baia_chatbot   view=embedded] In such case plugin displays default chat bot as modalles in the web page. You can also use the same chatbot with different views as modal or modalles in other webpages. When you want to use chat bot without modal then make sure that you selected pixels as  units of measurement for chatbot height in the bot configuration!
We also introduced view_mode attribute in shortcode. When you add such shortcode [s2baia_chatbot bot_id=BOT_HASH_CODE view_mode=fullscreen1 ] then chat bot will be displayed in full screen immediately after page loads. If you want to hide close button for full screen chat bot then you need to use hideclose=1 attribute. For ecxample [s2baia_chatbot bot_id=BOT_HASH_CODE view_mode=fullscreen1 hideclose=1]


== Apply custom CSS rules to any chatbot ==

In version 1.6.3, we added the "Deep Customization" feature.  This allows you to create a unique view for each chatbot using custom css rules. To do this, you need to find the 'Deep customization' section on the "Chatbots" or "Assistants" tabs, add the unique html id of the closed and opened chatbot. You can then add CSS rules to each closed and open view of any chatbot. Separate css rules can be applied to different chatbots.



== Users access ==

It is possible to configure user access to different parts of plugin. If you are admin then you can select which user roles have access to next functional parts: meta-boxes,  plugin's configuration page, access to delete instructions and access to configure chatbot.
You can also select which user role has access to the image generation feature. To do this, you need to select a user role from the middle select box in the 'User Roles' panel. It is worth noting that even if the role selected in the middle field has access to create images, this does not guarantee that this role will have permission to write created images to the Media Library. For more details, please check [page](https://wordpress.org/documentation/article/roles-and-capabilities/)
As an administrator, you can also select user roles that can configure chatbots. Also, when creating chatbots, you can choose whether they will be available to unregistered visitors or only to registered ones.

== Open AI ==

The S2B AI Assistant makes use of the API provided by [OpenAI](https://openai.com/blog/openai-api or [Reference]https://platform.openai.com/docs/api-reference). This plugin does not collect any data from your OpenAI account apart from the number of tokens used. The information sent to the OpenAI servers mainly includes the content of your article and the specified context. The usage information displayed in the add-on's settings is only for your reference. To obtain accurate information about your usage, it is important to check it on the [OpenAI website](https://platform.openai.com/account/usage). Additionally, please make sure to review their [Privacy Policy](https://openai.com/privacy/) and [Terms of Service](https://openai.com/terms/) for more details.

== Disclaimer ==


The S2B AI Assistant is a plugin that allows users to integrate their websites with AI services such as OpenAI's ChatGPT. In order to use this plugin, users must have their own API key and adhere to the guidelines provided by the chosen AI service. When utilizing the S2B AI Assistant, users are required to monitor and oversee the content produced by the AI, as well as handle any potential issues or misuse. The developer of the S2B AI Assistant plugin and other related parties cannot be held responsible for any problems or losses that may arise from the usage of the plugin or the content generated by the AI. Users are advised to consult with a legal expert and comply with the applicable laws in their jurisdiction. OpenAI, ChatGPT, and related marks are registered trademarks of OpenAI. Author of this plugin is not a partner of, endorsed by, or sponsored by OpenAI


== Screenshots ==
1. General settings of the plugin.
2. Models settings
3. Instructions settings
4. Edit/Generate content in post/page metabox of edit screen
5. Expert tab allows to to customize request to Chat completion API 
6. Chatbot minimized window
7. Chat bot maximized window
8. Chat bot styles configuration
9. Chat bot Assistant API Configuration
10. Chat bot discussion log
11. Create multiple chatbots with different styles and behavior
12. Chatbot configuration page
13. Image generation page
14. Store generated Image into Media Library 
15. Modalless option of chat bot 

== Frequently Asked Questions ==

= How many different chat bots can I create? = 
As many as you want. Each chatbot can have different position, size, colors, behavior and other elements. 

= Can I customize the appearance of the chatbot? = 
Yes. You can change position, size,  colors, behavior of each chatbot separately in Chatbots and Assistants tabs of configuration page.

= Which difference between chatbots and assistants? = 
Actually they are two different types of chatbot. Assistants use OpenAI Assistant API, while Chatbots use OpenAI completion API to answer visitor's questions. Using Assistant API you can upload file to add additional information  to chatbot's knowledge database. This allows you to create content aware chatbot.

= How can I change icon of chatbot =
First upload icon to Media Library. Then copy its url and paste it into "Url of chatbot picture:" field.

= Which OpenAI models does the plugin use? =
Any model that is accessible for your OpenAI account. You just need to open Settings page and switch to Models tab. After click 'Refresh from OpenAI'. All models will be attached to plugin. Then just select models you want to use.

= How does S2B AI Assistant handle image generation? =
S2B AI Assistant leverages the DALL-E model for image generation. You can specify prompts in the command line within the plugin, and S2B AI Assistant will generate unique images, logos, banners, and more, requiring no design skills from the user.

=The S2B AI Assistant is NOT working in the front end. =
The primary cause for this issue is often incorrect theme coding and jQuery being loaded from an external source. WordPress core already includes jQuery, and as per WordPress standards, it should be included using wp_enqueue_script. https://developer.wordpress.org/reference/functions/wp_enqueue_script/ . Verify if this is happening in your theme.



== Changelog ==
= 1.6.3 =
* Allow to apply custom css rules to any chatbot and assistant
* Fix sizes of textarea elements in configuration page

= 1.6.2 =
* Add feature to copy shordcode of chatbot
* Make default models: gpt-4o, gpt-4o-mini, gpt-4
= 1.6.1 =
* Add ability to run chatbot without modal window
* Add view_mode parameter which allows to modify view of chatbot via shortcode

= 1.5.9 =
* Added ability to show notification that explains users that conversation is logged
= 1.5.8 =
* Feature that allows you to create multiple different Assistants
= 1.5.5 =
* Fix deprecation message
= 1.5.4 =
* Fix logging feature

= 1.5.3 =
* Feature that allows create different chatbots for different pages. Removed outdated gpt 3.5 models as default.

= 1.5.2 =
* Conversation Logging feature added for other apis 

= 1.5.1 =
* Conversation Logging feature added 

= 1.4.2 =
* Fix configuration

= 1.4.1 =
* Added content aware AI chatbot  using Assistant API


= 1.3.3 =
* Small fixes

= 1.3.2 =
* Small fixes

= 1.3.1 =
* Added AI chatbot  feature

== Changelog ==
= 1.2.2 =
* Adding fixes

= 1.2.1 =
* Image generation feature added

= 1.1.2 =
* Security fixes

= 1.1.1 =
* Add feature that allows to change access to different parts and functions of plugin.

= 1.0.1 =
* Some ui improvement.

= 1.0.0 =
* Launch!
