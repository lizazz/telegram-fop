<?php

namespace Modules\Report\Services;

use App\Models\Fop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Report\Repositories\ReportRepository;
use SimpleXMLElement;

class XmlReportService
{
    public function __construct(public ReportRepository $reportRepository) {}
    public function createReport(Fop $fop): string
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><Файл></Файл>');
        $xml->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml->addAttribute('ИдФайл', md5($fop->name . $fop->tax_id . $fop->fop_id . $fop->created_at));
        $xml->addAttribute('ВерсПрог', "1С:БУХГАЛТЕРИЯ 3.0.156.17");
        $xml->addAttribute('ВерсФорм', "5.07");
        $document = $xml->addChild('Документ');
        $document->addAttribute('КНД', $fop->fop_id);
        $document->addAttribute('ДатаДок', Carbon::now()->format('d.m.Y'));
        $document->addAttribute('Период', 24);
        $document->addAttribute('ОтчетГод', 2024);
        $document->addAttribute('КодНО', 5009);
        $document->addAttribute('НомКорр', 0);
        $document->addAttribute('ПоМесту', 120);
        $svnp = $document->addChild('СвНП');
        $npfl = $svnp->addChild('НПФЛ');
        $npfl->addAttribute('ИННФЛ', $fop->tax_id);
        $fullname = $npfl->addChild('ФИО');
        $fullname->addAttribute('Фамилия', $fop->name);
        $fullname->addAttribute('Имя', $fop->name);
        $fullname->addAttribute('Отчество', $fop->name);
        $signer = $document->addChild('Подписант');
        $signer->addAttribute('ПрПодп', 1);
        $usn = $document->addChild('УСН');
        $sumNalPUNP = $usn->addChild('СумНалПУ_НП');
        $sumNalPUNP->addAttribute('ОКТМО', 46709000);
        $sumNalPUNP->addAttribute('НалПУУменПер', 0);
        $reschNal1 = $sumNalPUNP->addChild('РасчНал1');
        $reschNal1->addAttribute('ПризСтав', 1);
        $reschNal1->addAttribute('ПризНП', 2);
        $income = $reschNal1->addChild('Доход');
        $income->addAttribute('СумЗаПг', 40000);
        $income->addAttribute('СумЗа9м', 100000);
        $income->addAttribute('СумЗаНалПер', 194000);
        $bet = $reschNal1->addChild('Ставка');
        $bet->addAttribute('СтавкаКв', 6.0);
        $bet->addAttribute('СтавкаПг', 6.0);
        $bet->addAttribute('Ставка9м', 6.0);
        $bet->addAttribute('СтавкаНалПер', 6.0);
        $calc = $reschNal1->addChild('Исчисл');
        $calc->addAttribute('СумЗаПг', 2400);
        $calc->addAttribute('СумЗа9м', 6000);
        $calc->addAttribute('СумЗаНалПер', 11640);
        $umenNal = $reschNal1->addChild('УменНал');
        $umenNal->addAttribute('СумЗаПг', 2400);
        $umenNal->addAttribute('СумЗа9м', 6000);
        $umenNal->addAttribute('СумЗаНалПер', 11640);
        Storage::disk('local')->put('xml/'. $fop->fop_id.'.xml', $xml->asXML());
        $path = Storage::disk('local')->path('xml/'. $fop->fop_id.'.xml');


        Log::info($path);
        return $path;
    }
}
