import {Component, Input, ViewChild, ElementRef, Injectable} from "@angular/core";

import {ThemeService} from "../../../service/ThemeService";
import {Theme} from "../../../definitions/entity/Theme";

@Component({
    selector: 'cass-theme-select',
    template: require('./template.html'),
    providers: [
        ThemeService
    ],
    styles: [
        require('./style.shadow.scss')
    ]
})
@Injectable()
export class ThemeSelect
{
    private browser: ThemeSelectBrowser = new ThemeSelectBrowser(this, this.service);
    private search: ThemeSelectSearch = new ThemeSelectSearch(this, this.service);

    @ViewChild('searchInput') searchInput: ElementRef;
    @ViewChild('scrolling') scrolling: ElementRef;

    @Input('value') value;
    @Input('multiple') multiple = "true";

    constructor(private service: ThemeService) {
        this.browser.scrolling = this.scrolling;
    }

    isMultiple(): boolean {
        return this.multiple === "1" || this.multiple === "true";
    }

    has(themeId: number) {
        return ~this.value.indexOf(themeId);
    }

    exclude(themeId: number) {
        let index = this.value.indexOf(themeId);

        if(~index) {
            this.value.splice(index, 1);
        }
    }

    include(themeId: number) {
        if(!~this.value.indexOf(themeId)) {
            if(this.isMultiple()) {
                this.value.push(themeId);
                this.searchInput.nativeElement.value = '';
                this.search.disable();
            }else{
                this.value = [themeId];
                this.search.disable();
            }
        }
    }
}

class ThemeSelectSearch
{
    static MAX_RESULTS = 100;

    enabled: boolean = false;
    results: Theme[] = [];
    lastInput: string;

    constructor(private themeSelect: ThemeSelect, private service: ThemeService) {
    }

    isResultsAvailable(): boolean {
        return this.enabled && (this.results.length > 0);
    }

    update(input: string) {
        this.lastInput = input;

        this.results = this.fetch(input).filter((theme: Theme) => {
            return !this.themeSelect.has(theme.id);
        });
    }

    enable() {
        this.enabled = true;
    }

    disable() {
        this.enabled = false;
        this.results = [];
    }

    fetch(input: string) {
        var results = [];

        if(input.replace(/\s/g, '').length > 0) {
            this.service.each((theme: Theme) => {
                if(results.length <= ThemeSelectSearch.MAX_RESULTS) {
                    if(~theme.title.toLocaleLowerCase().indexOf(input.toLocaleLowerCase()) && !this.themeSelect.has(theme.id)) {
                        results.push(theme);
                    }
                }
            });
        }

        return results;
    }
}

class ThemeSelectBrowser
{
    public visible: boolean = false;
    public columns: Theme[] = [];
    public scrolling: ElementRef;

    constructor(private themeSelect: ThemeSelect, private service: ThemeService) {
        this.columns.push(service.getRoot());
    }

    isActive(compare: Theme): boolean {
        for(let tree of this.columns) {
            if(tree.id === compare.id) {
                return true;
            }
        }

        return false;
    }

    setColumn(level: number, tree: Theme) {
        for(let n = this.columns.length; n > level; n--) {
            this.columns.pop();
        }

        this.columns.push(tree);

        if(level === (this.columns.length - 1)) {
            this.themeSelect.scrolling.nativeElement.scrollLeft = 9999;
        }
    }

    show() {
        this.visible = true;
    }

    hide() {
        this.visible = false;
    }

    toggle() {
        this.visible = !this.visible;
    }
}

interface ThemeSelectBrowserColumn
{
    tree: Theme;
    next?: ThemeSelectBrowserColumn;
}