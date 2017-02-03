import { Injectable} from '@angular/core';
import {Http } from "@angular/http";
import { Observable } from "rxjs";

@Injectable()
export class ModuleService {
    constructor(private _http: Http) {}

    getModules() : Observable<Module[]> {
        let url = 'http://boilerplate.dev/admin/modules/';

        return this._http.get(url).map(res => Module.fromJSONArray(res.json()));
    }

    getModule(module) : Observable<Module> {
        let url = 'http://boilerplate.dev/admin/modules/' + module;

        return this._http.get(url).map(res => Module.fromJSON(res.json()));
    }
}

export class Module {
    public name: string;
    public module: string;
    public actions: ModuleActions;

    constructor(name: string, module: string, actions: ModuleActions) {
        this.name = name;
        this.module = module;
        this.actions = actions;
    }

    static fromJSONArray(array: Array<Object>): Module[] {
        return array.map(function(obj) {
            return Module.fromJSON(obj);
        });
    }

    static fromJSON(obj: Object): Module {
        return new Module(obj['name'], obj['module'], new ModuleActions(obj['actions']['index']));
    }
}

export class ModuleActions {
    public index: string;

    constructor(index: string) {
        this.index = index;
    }
}
