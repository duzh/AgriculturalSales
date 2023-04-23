<?php

namespace Mdg\Frontend\Controllers;

class MsgController extends ControllerBase
{
	public function showmsgAction() {
		$msg = $this->request->get('msg', 'string', '');
		$this->view->msg = $msg;
		$this->view->is_ajax = true;
	}

	public function merchantresultAction () {
		// $_POST['MSG'] = 'PE1TRz48TWVzc2FnZT48VHJ4UmVzcG9uc2U+PFJldHVybkNvZGU+MDAwMDwvUmV0dXJuQ29kZT48RXJyb3JNZXNzYWdlPr270tezybmmPC9FcnJvck1lc3NhZ2U+PEVDTWVyY2hhbnRUeXBlPkVCVVM8L0VDTWVyY2hhbnRUeXBlPjxNZXJjaGFudElEPjEwMzg4MTU1MDAwMDAwMzwvTWVyY2hhbnRJRD48VHJ4VHlwZT5BQkNDYXJkUGF5PC9UcnhUeXBlPjxPcmRlck5vPm1kZzExNTMyMDE1MDYyNjg3PC9PcmRlck5vPjxBbW91bnQ+MC4wMTwvQW1vdW50PjxCYXRjaE5vPjAwMDA3MTwvQmF0Y2hObz48Vm91Y2hlck5vPjAxNjAxNTwvVm91Y2hlck5vPjxIb3N0RGF0ZT4yMDE1LzA2LzI1PC9Ib3N0RGF0ZT48SG9zdFRpbWU+MjE6NDc6MjA8L0hvc3RUaW1lPjxNZXJjaGFudFJlbWFya3M+SGk8L01lcmNoYW50UmVtYXJrcz48UGF5VHlwZT5FUDAwNDwvUGF5VHlwZT48Tm90aWZ5VHlwZT4xPC9Ob3RpZnlUeXBlPjxpUnNwUmVmPjkwMTUwNjI1MjE0NzIwMDQyODM8L2lSc3BSZWY+PC9UcnhSZXNwb25zZT48L01lc3NhZ2U+PFNpZ25hdHVyZS1BbGdvcml0aG0+U0hBMXdpdGhSU0E8L1NpZ25hdHVyZS1BbGdvcml0aG0+PFNpZ25hdHVyZT5GaHBZcHJRbEVIcTBmcFl3WEQrSlg2NFRPakZCelVLZHExRXIrRkYyc0xmQlhWWEhDMDNheXlQdjBHYnl3WS8rOGEzVTZZcmZvL2ZtYXVsUHdaWDFkYjJjbG1Pb1J6dzJ6Mmd0Nlk3WEhKdFVXbFZOaWlMUDh5N0tTNGQyWDNERHdZTnlBYW01VUVTbktCQ3lLbis0cDdPMGN3bVhxUXBxaVVBZ216UDcyeW89PC9TaWduYXR1cmU+PC9NU0c+';
		include_once __DIR__.'/../../lib/abc/demo/MerchantResult.php';
		exit;
	
	}

}