import {Component, OnInit, Input, OnChanges, SimpleChanges} from '@angular/core';
import { Module } from "../Module/module.service";
import {IndexService} from "./index.service";
import { Ng2SmartTableModule } from 'ng2-smart-table';
import {Observable} from "rxjs";
import {LocalDataSource} from "ng2-smart-table/src/ng2-smart-table/lib/data-source/local/local.data-source";

@Component({
  selector: 'index',
  templateUrl: './index.component.html',
  styleUrls: ['./index.component.css'],
  providers: [IndexService]
})

export class IndexComponent implements OnChanges {
  source: LocalDataSource;
  settings;

  @Input() module: Observable<Module>;

  constructor(private _service: IndexService) {
    this.settings = {
      selectMode: 'multi',
      columns: {
        id: {
          title: 'ID'
        },
        name: {
          title: 'Name'
        }
      }
    };
  }

  ngOnChanges(changes: SimpleChanges): void {
    let module = changes['module'].currentValue;

    if (module.name) {
      this._service.getRecords(module.actions.index).subscribe(res => this.source = new LocalDataSource(res));
    }
  }


  onSearch(query: string = ''): void {
    this.source.setFilter([
      // fields we want to include in the search
      {
        field: 'id',
        search: query
      },
      {
        field: 'name',
        search: query
      }
    ], false);
    // second parameter specifying whether to perform 'AND' or 'OR' search
    // (meaning all columns should contain search query or at least one)
    // 'AND' by default, so changing to 'OR' by setting false here
  }
}
