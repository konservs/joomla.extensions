<?php
/**
 * Sets of functions and classes to work with...
 *
 * @author Andrii Biriev
 * @author Andrii Karepin
 * @copyright © Brilliant IT corporation, www.it.brilliant.ua
 */
class BChinaCalc{
	public static $instance=NULL;
	protected static $goodstype_avia=NULL;
	protected static $denttype_avia=NULL;
	protected static $rules_express=NULL;
	/**
	 * Get singleton instance...
	 */
	public static function getInstance(){
		if(!is_object(self::$instance)){
			self::$instance=new BChinaCalc();
			}
		return self::$instance;
		}
	/**
	 * Экспресс доставка (3-4 дня) - получение тарифов.
	 *
	 * Пока хардкод, все тарифы менять тут. В будущем возможна
	 * подгрузка из базы, кеширование и админка (необходимо
	 * доработать библиотеку и компонент).
	 */
	public function getexpressrules(){
		self::$rules_express=array(
			(object)array(
				'id'=>1,
				'mass_from'=>0,
				'mass_to'=>10,
				'cost_weight'=>15.0 //Cost per KG.
				),
			(object)array(
				'id'=>2,
				'mass_from'=>10,
				'mass_to'=>20,
				'cost_weight'=>14.0 //Cost per KG.
				),
			(object)array(
				'id'=>3,
				'mass_from'=>20,
				'mass_to'=>100,
				'cost_weight'=>13.0 //Cost per KG.
				),
			(object)array(
				'id'=>4,
				'mass_from'=>100,
				'mass_to'=>999999,
				'cost_weight'=>11.0 //Cost per KG.
				)
			);
		return self::$rules_express;
		}
	/**
	 * Получение списка правил доплаты к доставке для плотности.
	 *
	 * Пока хардкод, все данные менять тут. В будущем возможна
	 * подгрузка из базы, кеширование и админка (необходимо
	 * доработать библиотеку и компонент).
	 */
	public function getdentrules(){
		self::$denttype_avia=array(
			(object)array(
				'id'=>1,
				'dent_from'=>40,
				'dent_to'=>60,
				'cost_mass'=>5.0, //Cost per KG.
				'message'=>'Ваша плотность значительно меньше базовой, необходимо доплатить 5$ за каждый килограм.'
				),
			(object)array(
				'id'=>2,
				'dent_from'=>60,
				'dent_to'=>80,
				'cost_mass'=>3.0, //Cost per KG.
				'message'=>'Ваша плотность меньше базовой, необходимо доплатить 3$ за каждый килограм.'
				),
			(object)array(
				'id'=>3,
				'dent_from'=>80,
				'dent_to'=>120,
				'cost_mass'=>2.0, //Cost per KG.
				'message'=>'Ваша плотность меньше базовой, необходимо доплатить 2$ за каждый килограм.'
				),
			(object)array(
				'id'=>4,
				'dent_from'=>120,
				'dent_to'=>150,
				'cost_mass'=>0.5, //Cost per KG.
				'message'=>'Ваша плотность незначительно меньше базовой, необходимо доплатить 0.5$ за каждый килограм.'
				),
			(object)array(
				'id'=>5,
				'dent_from'=>150,
				'dent_to'=>200,
				'cost_mass'=>0, //Cost per KG.
				'message'=>'Ваша плотность - базовая. Доплата не требуется.'
				),
			(object)array(
				'id'=>6,
				'dent_from'=>200,
				'dent_to'=>230,
				'cost_mass'=>-0.5, //Cost per KG.
				'message'=>'Ваша плотность больше базовой, вам начисляется скидка - 0.5$ за каждый килограм.'
				),
			(object)array(
				'id'=>7,
				'dent_from'=>230,
				'dent_to'=>99999999,
				'cost_mass'=>-1.0, //Cost per KG.
				'message'=>'Ваша плотность значительно больше базовой, вам начисляется скидка - 1$ за каждый килограм.'
				)
			);
		return self::$denttype_avia;
		}
	/**
	 * Получение списка тарифов.
	 *
	 * Пока хардкод, все данные менять тут. В будущем возможна
	 * подгрузка из базы, кеширование и админка (необходимо
	 * доработать библиотеку и компонент).
	 */
	public function getgoods(){
		self::$goodstype_avia=array(
			(object)array(
				'id'=>1,
				'name'=>'Текстиль',
				'cost_weight'=>6.5,
				'cost_count'=>0,
				'insurance'=>1.5
				),
			(object)array(
				'id'=>2,
				'name'=>'Текстиль бренд',
				'cost_weight'=>11.0,
				'cost_count'=>0,
				'insurance'=>1.5
				),
			(object)array(
				'id'=>3,
				'name'=>'Обувь',
				'cost_weight'=>6.0,
				'cost_count'=>0,
				'insurance'=>1.5
				),
			(object)array(
				'id'=>4,
				'name'=>'Обувь бренд',
				'cost_weight'=>11.0,
				'cost_count'=>0,
				'insurance'=>1.5
				),
			(object)array(
				'id'=>5,
				'name'=>'Сумка',
				'cost_weight'=>6.0,
				'cost_count'=>0,
				'insurance'=>1.5
				),
			(object)array(
				'id'=>6,
				'name'=>'Сумка бренд',
				'cost_weight'=>11.0,
				'cost_count'=>0,
				'insurance'=>1.5
				),
			(object)array(
				'id'=>7,
				'name'=>'Хоз. товары не бренд, не электро',
				'cost_weight'=>6.0,
				'cost_count'=>0,
				'insurance'=>1.5
				),
			(object)array(
				'id'=>8,
				'name'=>'Хоз. товары бренд',
				'cost_weight'=>11.0,
				'cost_count'=>0,
				'insurance'=>1.5
				),
			(object)array(
				'id'=>9,
				'name'=>'Электроника',
				'cost_weight'=>9.0,
				'cost_count'=>0,
				'insurance'=>2.0
				),
			(object)array(
				'id'=>10,
				'name'=>'Телефон мобильный',
				'cost_weight'=>12.5,
				'cost_count'=>0,
				'insurance'=>2.0
				),
			(object)array(
				'id'=>11,
				'name'=>'Телефон мобильный бренд',
				'cost_weight'=>14.5,
				'cost_count'=>4.0,
				'insurance'=>2.0
				),
			(object)array(
				'id'=>12,
				'name'=>'ПК, планшетный ПК',
				'cost_weight'=>12.5,
				'cost_count'=>0,
				'insurance'=>2.0
				),
			(object)array(
				'id'=>13,
				'name'=>'ПК, планшетный ПК бренд',
				'cost_weight'=>12.5,
				'cost_count'=>4.0,
				'insurance'=>2.0
				),
			(object)array(
				'id'=>14,
				'name'=>'Изделия из кожи (куртки, дубленки)',
				'cost_weight'=>11.5,
				'cost_count'=>0,
				'insurance'=>2.0,
				'insurance_status'=>'necessary'//Страховка обязательна
				),
			(object)array(
				'id'=>15,
				'name'=>'Мех >1000$',
				'cost_weight'=>19.0,
				'cost_count'=>0,
				'insurance'=>2.0,
				'insurance_status'=>'necessary'//Страховка обязательна
				),
			(object)array(
				'id'=>16,
				'name'=>'Мех <1000$',
				'cost_weight'=>11.0,
				'cost_count'=>0,
				'insurance'=>2.0,
				'insurance_status'=>'necessary'//Страховка обязательна
				),
			(object)array(
				'id'=>17,
				'name'=>'Косметика',
				'cost_weight'=>9.0,
				'cost_count'=>0,
				'insurance'=>2.0,
				'insurance_status'=>'na'//Не страхуем
				),
			(object)array(
				'id'=>18,
				'name'=>'Часы наручные',
				'cost_weight'=>8.0,
				'cost_count'=>0,
				'insurance'=>2.0
				),
			(object)array(
				'id'=>19,
				'name'=>'Продукты питания',
				'cost_weight'=>14.5,
				'cost_count'=>0,
				'insurance'=>0,
				'insurance_status'=>'na'//Не страхуем
				),
			(object)array(
				'id'=>20,
				'name'=>'Медикаменты и пищевые добавки',
				'cost_weight'=>8.0,
				'cost_count'=>0,
				'insurance'=>0,
				'insurance_status'=>'na'//Не страхуем
				),
			(object)array(
				'id'=>21,
				'name'=>'Часы наручные бренд',
				'cost_weight'=>8.0,
				'cost_count'=>0,
				'insurance'=>2.0,
				'insurance_status'=>''//Страховка по желанию
				),
			(object)array(
				'id'=>22,
				'name'=>'Одежда бренд',
				'cost_weight'=>11.0,
				'cost_count'=>0,
				'insurance'=>2.0,
				'insurance_status'=>''//Страховка по желанию
				),
			(object)array(
				'id'=>23,
				'name'=>'Каски, бронежилеты',
				'cost_weight'=>8.0,
				'cost_count'=>0,
				'insurance'=>0,
				'insurance_status'=>'na'//Не страхуем
				),
			(object)array(
				'id'=>24,
				'name'=>'Каски с Франкфурта',
				'cost_weight'=>7.0,
				'cost_count'=>0,
				'insurance'=>0,
				'insurance_status'=>'na'//Не страхуем
				),
			(object)array(
				'id'=>25,
				'name'=>'Чай, кофе',
				'cost_weight'=>8.0,
				'cost_count'=>0,
				'insurance'=>0,
				'insurance_status'=>'na'//Не страхуем
				),
			(object)array(
				'id'=>26,
				'name'=>'Жидкости (краски, чернила и т. п.)',
				'cost_weight'=>8.0,
				'cost_count'=>0,
				'insurance'=>0,
				'insurance_status'=>'na'//Не страхуем
				),
			(object)array(
				'id'=>27,
				'name'=>'Раскладные ножи',
				'cost_weight'=>7.0,
				'cost_count'=>0,
				'insurance'=>0,
				'insurance_status'=>'na'//Не страхуем
				),
			(object)array(
				'id'=>28,
				'name'=>'Прицелы',
				'cost_weight'=>0,
				'cost_count'=>0,
				'insurance'=>2.0
				),
			);
		return self::$goodstype_avia;
		}
	}
