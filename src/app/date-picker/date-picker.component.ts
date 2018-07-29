import { Component, OnInit } from '@angular/core';
import { Date } from '../date';
import { DateService } from '../services/date.service';
import {NgbModule, NgbDateStruct} from '@ng-bootstrap/ng-bootstrap';
import { log } from 'util';

@Component({
  selector: 'app-date-picker',
  templateUrl: './date-picker.component.html',
  styleUrls: ['./date-picker.component.css']
})
export class DatePickerComponent {

  public startDate;
  public endDate;

  constructor(private dateService: DateService) { }

  onClick(event) {
    let startDateStr = this.startDate.year + '-' + this.startDate.month + '-' + this.startDate.day;
    let endDateStr = this.endDate.year + '-' + this.endDate.month + '-' + this.endDate.day;

    let result = this.dateService.calculateDate(startDateStr, endDateStr);
  }

}
