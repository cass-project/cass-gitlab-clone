import {Component, Input, Output} from "@angular/core";

import {Theme, THEME_PREVIEW_PUBLIC_PREFIX} from "../../../definitions/entity/Theme";
import {EventEmitter} from "@angular/common/src/facade/async";

@Component({
    selector: 'cass-theme-card-table',
    template: require('./template.jade'),
    styles: [
        require('./style.shadow.scss'),
    ]
})
export class ThemeCardTable
{
    private prefix: string = THEME_PREVIEW_PUBLIC_PREFIX;

    @Input('theme') theme: Theme;
    @Output('click') clickEvent: EventEmitter<Theme> = new EventEmitter<Theme>();

    click() {
        this.clickEvent.emit(this.theme);
    }
}