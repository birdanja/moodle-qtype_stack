<?php
// This file is part of Stack - http://stack.ed.ac.uk/
//
// Stack is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Stack is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Stack.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This script runs the answers tests and verifies the results.
 *
 * This serves two purposes. First, it verifies that the answer tests are working
 * correctly, and second it serves to document the expected behaviour of answer
 * tests, which is useful for learning how they work.
 *
 * @copyright  2012 University of Birmingham
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


class stack_subscripts_test_data {
    const RAWINPUT = 0; // What a student might type.
    const MAXIMA   = 1; // Correct maxima syntax, as extended by STACK.
    const TEX      = 2; // TeX output from stack_disp.
    const NOTES    = 3;

    /* Raw data should be in the following form.
     * Input, as a raw (but syntactically valid, string.
     * Maxima representation
     * TeX string
     */
    protected static $rawdata = array(
        array('Delta*v_x', 'Delta*v_x', '\Delta\,{v}_{x}'),
        array('Delta*v_x0', 'Delta*v_x0', '\Delta\,{v}_{{\it x_0}}'),
        array('Delta*v_xi', 'Delta*v_xi', '\Delta\,{v}_{\xi}', 'xi is a Greek letter...'),
        array('v_0', 'v_0', '{v}_{0}'),
        array('v_y0', 'v_y0', '{v}_{{\it y_0}}'),
        array('v_x0^2', 'v_x0^2', '{v}_{{\it x_0}}^2'),
        array('v_1^2', 'v_1^2', '{v}_{1}^2'),
        array('v_s', 'v_s', '{v}_{s}'),
        array('m_a', 'm_a', '{m}_{a}'),
        array('a_x', 'a_x', '{a}_{x}'),
        array('a_1x', 'invalid', '', '1x is not a valid Maxima atom.'),
        array('texsub(a,1*x)', 'texsub(a,1*x)', '{a}_{1\,x}'),
        array('a_cm', 'a_cm', '{a}_{{\it cm}}', 'Do we mind about Roman typeface here for units?'),
        array('F_1x', 'invalid', ''),
        array('texsub(F,1*x)', 'texsub(F,1*x)', '{F}_{1\,x}'),
        array('F_1-2', 'F_1-2', '{F}_{1}-2', 'How do we bind into a single subscript?  We need a display function'),
        array('texsub(F,1-2)', 'texsub(F,1-2)', '{F}_{1-2}', 'How do we bind into a single subscript?  We need a display function'),
        array('P_min', 'P_min', '{P}_{{\it min}}'),
        array('P_max', 'P_max', '{P}_{{\it max}}'),
        array('F_max', 'F_max', '{F}_{{\it max}}'),
        array('F_net', 'F_net', '{F}_{{\it net}}', 'The function net is not known to Maxima.  In this case it has been added, but in general studnets are only permitted to add known tokens with two letters.'),
        array('omega_a', 'omega_a', '{\omega}_{a}'),
        array('omega_0', 'omega_0', '{\omega}_{0}'),
        array('omega_0^2', 'omega_0^2', '{\omega}_{0}^2'),
        array('r_1', 'r_1', '{r}_{1}'),
        array('r_1^2', 'r_1^2', '{r}_{1}^2'),
        array('r_01', 'r_01', '{r}_{1}', 'Multiplication by zero here removes the leading zero.'),
        array('texsub(r,0*1)', 'texsub(r,0*1)', '{r}_{0\,1}'),
        array('r1', 'r1', '{\it r_1}', 'By default Maxima displays atoms with tailing numbers using subscripts.  This is not algebraically equivalent to r_1.'),
        array('ab1', 'ab1', '{\it ab_1}', ''),
        array('Theta1', 'Theta1', '{\it Theta_1}'),
        array('Theta_1', 'Theta_1', '{\Theta}_{1}'),
        array('cos(Theta_1)', 'cos(Theta_1)', '\cos \left( {\Theta}_{1} \right)'),
        array('sin(Theta_1)', 'sin(Theta_1)', '\sin \left( {\Theta}_{1} \right)'),
        array('U_E,a', 'invalid', '', 'We do not accept unencapsulated commas.'),
        array('T_1/2', 'T_1/2', '\frac{{T}_{1}}{2}', 'Again, we need to use texsub'),
        array('texsub(T,1/2)', 'texsub(T,1/2)', '{T}_{\frac{1}{2}}'),
        array('a_b_c', 'a_b_c', '{{a}_{b}}_{c}'),
        array('(a_b)_c', 'invalid', '', 'Test associativity...'),
        array('a_(b_c)', 'invalid', ''),
        array('texsub(texsub(a,b),c)', 'texsub(texsub(a,b),c)', '{{a}_{b}}_{c}'),
        array('texsub(a,texsub(b,c))', 'texsub(a,texsub(b,c))', '{a}_{{b}_{c}}'),
        array('a_theta1', 'a_theta1', '{a}_{{\it theta_1}}'),
    );

    public static function get_raw_test_data() {
        return self::$rawdata;
    }

    public static function test_from_raw($data) {
        $test = new stdClass();
        $test->rawinput      = $data[self::RAWINPUT];
        $test->maxima        = $data[self::MAXIMA];
        $test->tex           = $data[self::TEX];
        $test->notes         = '';
        if (array_key_exists(self::NOTES, $data)) {
            $test->notes = $data[self::NOTES];
        }
        $test->valid         = '';
        $test->value         = '';
        $test->display       = '';
        $test->errors        = '';
        return $test;
    }

    public static function run_test($test, $simp) {

        $cs = array('p:'.$test->rawinput);
        foreach ($cs as $s) {
            $cs = new stack_cas_casstring($s);
            // 
            $cs->get_valid('s', true, 0);
            $s1[] = $cs;
        }
        $options = new stack_options();
        $options->set_option('simplify', $simp);
        $options->set_option('multiplicationsign', 'none');

        $at1 = new stack_cas_session($s1, $options, 0);
        $at1->instantiate();

        $cs = $at1->get_session();
        $cs = $cs[0];
        $test->valid = $cs->get_valid();
        $test->value = $cs->get_value();
        $test->display = $cs->get_display();
        $test->errors = $cs->get_errors();
        return($test);
    }
}