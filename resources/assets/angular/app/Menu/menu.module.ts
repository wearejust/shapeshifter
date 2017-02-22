import { Component } from '@angular/core';
import { ModuleService } from '../Module/module.service';
import 'rxjs/add/operator/map'

@Component({
    moduleId: module.id,
    selector: 'menu',
    templateUrl: './menu.html',
    providers: [ ModuleService ]
    // styleUrls: [ './dashboard.component.css' ]
})

export class MenuComponent {
    items = [];
    private routing: ModuleService;

    constructor(routing: ModuleService) {
        this.routing = routing;

        this.routing.getModules().subscribe(
            items => this.items = items
        );
    }
}
