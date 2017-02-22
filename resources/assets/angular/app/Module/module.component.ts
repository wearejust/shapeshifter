import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ModuleService, Module, ModuleActions }   from './module.service';

@Component({
    moduleId: module.id,
    selector: 'module',
    templateUrl: './module.html',
    providers: [ ModuleService ]
    // styleUrls: [ './dashboard.component.css' ]
})

export class ModuleComponent implements OnInit {
    public module = new Module('', '', new ModuleActions(''));

    constructor(private route: ActivatedRoute, private service: ModuleService) {}

    ngOnInit(): void {

        this.route.params.subscribe(params => {

            let name = params['name'];

            this.service.getModule(name)
                .subscribe(module => this.module = module);
        });
    }
}
