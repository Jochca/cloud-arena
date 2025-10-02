import { Controller } from '@hotwired/stimulus';
import { getComponent } from '@symfony/ux-react';

export default class extends Controller {
    connect() {
        console.log('Hello from Stimulus!');

        const HelloWorld = getComponent('HelloWorld');

        this.element.appendChild(
            HelloWorld({})
        );
    }
}
