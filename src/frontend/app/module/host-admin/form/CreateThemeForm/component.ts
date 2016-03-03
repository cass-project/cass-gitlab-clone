import {Component} from 'angular2/core';
import {ThemeRESTService} from  '../../../theme/service/ThemeRESTService';
import {ThemeEditorService} from '../../service/ThemeEditorService';
import {RouteConfig, ROUTER_DIRECTIVES, Router} from 'angular2/router';


@Component({
    styles: [
        require('./style.shadow.scss')
    ],
    template: require('./template.html')
})
export class CreateThemeForm
{
    title: string;

    constructor(
        private themeRESTService: ThemeRESTService,
        private themeEditorService: ThemeEditorService,
        public router: Router
    ){}
    submit() {
        console.log(this.title);
        this.themeRESTService.createTheme(this.title, this.themeEditorService.selectedThemeId);
        this.themeRESTService.getThemesTree().map(res => res.json()).subscribe(data => this.themeEditorService.themesTree = data['entities']);
        this.router.parent.navigate(['Theme-Editor']);  //This navigate return full page reload, dunno why, need investigate
    }
}