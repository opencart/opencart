import { Controller } from '../component.js';
import { loader } from '../index.js';
import './article_list.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('cms/topic');


// Storage
let topics = await loader.storage('topic/topic');

export default class extends Controller {
    async render() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

       data.topic_id = 0;

        if (request.has('topic_id')) {
            data.topic_id = request.get('topic_id');
        }

        data.heading_title = language.heading_title;
        data.image = '';
        data.description = '';

        // If Topic ID is set
        let topic = await loader.storage('topic/topic-' + data.topic_id);

        if (topic !== undefined && config.config_language in topic.description) {
            data.image = topic.image;

            let description = topic.description[config.config_language];

            data.name = description.name;
            data.description = description.description;
        }

        data.topics = [];

        for (let topic of topics) {
            if (config.config_language in topic.description) {
                let description = topic.description[config.config_language];

                data.topics.push({
                    topic_id: topic.topic_id,
                    name: description.name
                });
            }
        }

        data.search = '';

        return loader.template('cms/topic', { ...data, ...language });
    }

    async onSubmit(e) {
        e.preventDefault();

        let url = 'index.php?route=cms/topic&language={{ language }}';

        var search = $('#input-search').val();

        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }

        var topic_id = $('#input-topic').prop('value');

        if (topic_id > 0) {
            url += '&topic_id=' + topic_id;
        }

        location = url;
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.target.getAttribute('href');
    }
}