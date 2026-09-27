import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';
import './article_list.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('cms/topic');

// Storage
let topics = await loader.storage('topic/topic');

export default class CmsTopic extends WebComponent {
    async render() {
        let data = new Map();

        data.set('topic_id', 0);
        data.set('heading_title', language.get('heading_title'));
        data.set('description', '');
        data.set('image', '');

        // If Topic ID is set
        let topic = await loader.storage('topic/topic-' + this.getAttribute('topic_id'));

        if (topic instanceof Map && local.get('language') in topic.get('description')) {
            let description = topic.get('description')[local.get('language')];

            data.set('topic_id', topic.get('topic_id'));
            data.set('name', description.name);
            data.set('description', description.description);
            data.set('image', topic.image);
        }

        data.set('topics', []);

        for (let topic of topics) {
            if (local.get('language') in topic.description) {
                let description = topic.description[local.get('language')];

                data.get('topics').push({
                    topic_id: topic.topic_id,
                    name: description.name
                });
            }
        }

        data.set('search', '');

        return loader.template('cms/topic', [ data, language ]);
    }

    async onSubmit(e) {
        e.preventDefault();

        let url = 'action.php?route=cms/topic&language=' + local.get('language');

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
}

customElements.define('cms-topic', CmsTopic);