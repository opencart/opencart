import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('cms/topic');

export default class extends Controller {
    async render() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

        let topic = await loader.storage('cms/topic-' + request.get('topic_id'));

        if (topic !== undefined && config.config_language in topic.description) {
            data.topic_id = topic.topic_id;

            let description = topic.description[config.config_language];

            data.name = description.name;
            data.description = description.description;
            data.image = topic.image;
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