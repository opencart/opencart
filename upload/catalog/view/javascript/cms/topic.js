import { Controller } from '../component.js';
import { loader } from '../index.js';
import './article_list.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('cms/topic');

export default class extends Controller {
    async render() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

        let topic_id = 0;

        if (request.has('topic_id')) {
            topic_id = request.get('topic_id');
        }

        let topic = await loader.storage('topic/topic-' + topic_id);

        if (topic !== undefined && config.config_language in topic.description) {
            data.topic_id = topic.topic_id;
            data.image = topic.image;

            let description = topic.description[config.config_language];

            data.name = description.name;
            data.description = description.description;
        }

        return loader.template('cms/topic', { ...data, ...language });
    }

    async onSubmit() {
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

    onKeyDown() {
        if (e.keyCode == 13) {
            $('#button-search').trigger('click');
        }
    }
}