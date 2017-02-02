import {Component, OnInit, Input} from '@angular/core';
import { Module } from "../Module/module.service";
import {IndexService} from "./index.service";
import { Ng2SmartTableModule } from 'ng2-smart-table';

@Component({
  selector: 'index',
  templateUrl: './index.component.html',
  styleUrls: ['./index.component.css'],
  providers: [IndexService]
})

export class IndexComponent implements OnInit {
  @Input() module: string;

  items = [];

  constructor(private _service: IndexService) {}

  ngOnInit() {
    // let url = this.module.actions.index;
    console.log(this.module);
    // this._service.getRecords(url).subscribe(
    //     items => this.items = items
    // )
  }
}
