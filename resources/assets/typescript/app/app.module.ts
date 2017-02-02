import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

import { AppRoutingModule } from './Routing/routing.module';
import { MenuComponent } from './Menu/menu.module';
import { ModuleComponent }   from './Module/module.component';

import { AppComponent } from './app.component';
import { IndexComponent } from './index/index.component';
import {Ng2SmartTableModule} from "ng2-smart-table/src/ng2-smart-table.module";

@NgModule({
  declarations: [
    MenuComponent,
    AppComponent,
    ModuleComponent,
    IndexComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    AppRoutingModule,
    Ng2SmartTableModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
